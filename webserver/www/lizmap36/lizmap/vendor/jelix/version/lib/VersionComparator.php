<?php
/**
* @author      Laurent Jouanneau
* @copyright   2008-2017 Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     MIT
*/

namespace Jelix\Version;

/**
 * class to compare version numbers. it supports the following keywords:
 * "pre", "-dev", "b", "beta", "a", "alpha".
 * It supports also the "*" wildcard. This wildcard must be the last part
 * of the version number.
 */
class VersionComparator
{
    /**
     * Compare two version objects.
     *
     * @return int
     *             - 0 if versions are equals
     *             - -1 if $version1 is lower than $version2
     *             - 1 if $version1 is higher than $version2
     */
    public static function compare(Version $version1, Version $version2)
    {
        if ($version1->hasWildcard() && $version2->hasWildcard()) {
            throw new \Exception("Cannot compare two wildcard versions");
        }
        if ($version1->hasWildcard()) {
            if ($version1->getMajor() === '*') {
                return 0;
            }
            list($v1, $v2) = self::getBoundsFromWildcardVersion($version1);

            if (self::compareVersionRange($version2, self::getRangeOperatorFromBounds($v1, $v2))) {
                return 0;
            }

            $ltRange = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v1);
            if (self::compareVersionRange($version2, $ltRange)) {
                return 1;
            }
            return -1;
        }
        else if ($version2->hasWildcard()) {
            if ($version2->getMajor() === '*') {
                return 0;
            }
            list($v1, $v2) = self::getBoundsFromWildcardVersion($version2);

            if (self::compareVersionRange($version1, self::getRangeOperatorFromBounds($v1, $v2))) {
                return 0;
            }
            $ltRange = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v1);
            if (self::compareVersionRange($version1, $ltRange)) {
                return -1;
            }
            return 1;
        }
        if ($version1->toString() == $version2->toString()) {
            return 0;
        }

        $v1 = $version1->getVersionArray();
        $v2 = $version2->getVersionArray();

        if (count($v1) > count($v2)) {
            $v2 = array_pad($v2, count($v1), 0);
        } elseif (count($v1) < count($v2)) {
            $v1 = array_pad($v1, count($v2), 0);
        }

        // version comparison
        foreach ($v1 as $k => $v) {
            if ($v === $v2[$k]) {
                continue;
            }
            if ($v < $v2[$k]) {
                return -1;
            } else {
                return 1;
            }
        }

        // stability comparison
        // dev/pre < alpha < beta < RC < stable
        $s1 = $version1->getStabilityVersion();
        $s2 = $version2->getStabilityVersion();
        if (count($s1) > count($s2)) {
            $s2 = array_pad($s2, count($s1), '');
        } elseif (count($s1) < count($s2)) {
            $s1 = array_pad($s1, count($s2), '');
        }

        foreach ($s1 as $k => $v) {
            if ($v === '*' || $s2[$k] === '*') {
                return 0;
            }
            if ($v === $s2[$k]) {
                continue;
            }
            if ($v === '') {
                if (!is_numeric($s2[$k])) {
                    return 1;
                } else {
                    $v = '0';
                }
            } elseif ($s2[$k] === '') {
                if (!is_numeric($v)) {
                    return -1;
                } else {
                    $s2[$k] = '0';
                }
            }
            if (is_numeric($v)) {
                if (is_numeric($s2[$k])) {
                    $v1 = intval($v);
                    $v2 = intval($s2[$k]);
                    if ($v1 == $v2) {
                        continue;
                    }
                    if ($v1 < $v2) {
                        return -1;
                    }
                    return 1;
                } else {
                    return -1;
                }
            } elseif (is_numeric($s2[$k])) {
                return -1;
            } else {
                if ($v === 'dev' || $v === 'pre') {
                    $v = 'aaaaaaaaaa';
                }
                $v2 = $s2[$k];
                if ($v2 === 'dev' || $v2 === 'pre') {
                    $v2 = 'aaaaaaaaaa';
                }
                $r = strcmp($v, $v2);
                if ($r > 0) {
                    return 1;
                } elseif ($r < 0) {
                    return -1;
                }
            }
        }

        $sv1 = $version1->getSecondaryVersion();
        $sv2 = $version2->getSecondaryVersion();

        if (($sv1 instanceof Version) || ($sv2 instanceof Version)) {
            if (($sv1 instanceof Version) && ($sv2 instanceof Version)) {
                return self::compare($sv1, $sv2);
            }
            else if ($sv1 instanceof Version) {
                return 1;
            }
            else if ($sv2 instanceof Version) {
                return -1;
            }
        }
        return 0;
    }

    /**
     * Compare two version as string.
     *
     * It supports wildcard in one of the version
     *
     * @param string $version1
     * @param string $version2
     *
     * @return int 0 if equal, -1 if $version1 < $version2, 1 if $version1 > $version2
     */
    public static function compareVersion($version1, $version2)
    {
        if ($version1 == $version2) {
            return 0;
        }

        $v1 = Parser::parse($version1);
        $v2 = Parser::parse($version2);

        return self::compare($v1, $v2);
    }

    protected static function normalizeVersionNumber(&$n)
    {
        $n[2] = strtolower($n[2]);
        if ($n[2] === 'pre' || $n[2] === 'dev' || $n[2] === '-dev') {
            $n[2] = '_';
            $n[3] = '';
            $n[4] = 'dev';
        }
        if (!isset($n[4])) {
            $n[4] = '';
        } else {
            $n[4] = strtolower($n[4]);
            if ($n[4] === 'pre' || $n[4] === '-dev') {
                $n[4] = 'dev';
            }
        }

        if ($n[2] === 'a') {
            $n[2] = 'alpha';
        } elseif ($n[2] === 'b') {
            $n[2] = 'beta';
        } elseif ($n[2] === '') {
            $n[2] = 'zzz';
        }
    }

    /**
     * create a string representing a version number in a manner that it could
     * be easily to be compared with an other serialized version. useful to
     * do comparison in a database for example.
     *
     * It doesn't support all version notation. Use serializeVersion2 instead.
     *
     * @param int $starReplacement 1 if it should replace by max value, 0 for min value
     * @deprecated
     */
    public static function serializeVersion($version, $starReplacement = 0, $pad = 4)
    {
        $vers = explode('.', $version);
        $r = '/^([0-9]+)([a-zA-Z]*|pre|-?dev)([0-9]*)(pre|-?dev)?$/';
        $sver = '';

        foreach ($vers as $k => $v) {
            if ($v === '*') {
                --$k;
                break;
            }

            $pm = preg_match($r, $v, $m);
            if ($pm) {
                self::normalizeVersionNumber($m);

                $m[1] = str_pad($m[1], ($k > 1 ? 10 : 3), '0', STR_PAD_LEFT);
                $m[2] = substr($m[2], 0, 1); // alpha/beta
                $m[3] = ($m[3] === '' ? '99' : str_pad($m[3], 2, '0', STR_PAD_LEFT)); // alpha/beta number
                $m[4] = ($m[4] === 'dev' ? 'd' : 'z');
                if ($k) {
                    $sver .= '.';
                }
                $sver .= $m[1].$m[2].$m[3].$m[4];
            } else {
                throw new \Exception("version number '" . $version . "' cannot be serialized");
            }
        }
        for ($i = $k + 1; $i < $pad; ++$i) {
            if ($i > 0) {
                $sver .= '.';
            }
            if ($starReplacement > 0) {
                $sver .= ($i > 1 ? '9999999999' : '999').'z99z';
            } else {
                $sver .= ($i > 1 ? '0000000000' : '000').'a00a';
            }
        }

        return $sver;
    }

    /**
     * create a string representing a version number in a manner that it could
     * be easily to be compared with an other serialized version. useful to
     * do comparison in a database for example.
     *
     * It does not serialize secondary versions, so you should serialize them separatly.
     * 
     * @param string $version
     * @param int $starReplacement 1 if it should replace '*' by max value, 0 for min value
     */
    public static function serializeVersion2($version, $starReplacement = 0, $maxpad = 10)
    {
        // remove meta data
        $vers = explode('+', $version, 2);
        $version = $vers[0];

        // remove secondary version
        $allVersions = preg_split('/(-|:)([0-9]+)($|\.|-)/', $version, 2, PREG_SPLIT_DELIM_CAPTURE);
        $version = $allVersions[0];

        $version = preg_replace("/([0-9])([a-z])/i", "\\1-\\2", $version);
        $version = preg_replace("/([a-z])([0-9])/i", "\\1.\\2", $version);
        $extensions = explode('-', $version, 3);
        $serial = '';
        $extensions = array_pad($extensions, 3, '0');
        foreach ($extensions as $ext) {
            $currentUnstable = 'z';
            $vers = explode('.', $ext);
            $vers = array_pad($vers, 5, "0");

            foreach($vers as $k => $v) {
                $pad = ($k > 1 ? $maxpad : 3);
                if ($v === '*') {
                    if ($starReplacement > 0) {
                        $vers[$k] = $currentUnstable.($k > 1 ? '9999999999' : '999');
                    } else {
                        $vers[$k] = $currentUnstable.($k > 1 ? '0000000000' : '000');
                    }
                }
                else if (is_numeric($v)) {
                    $vers[$k] = $currentUnstable.str_pad($v, $pad, '0', STR_PAD_LEFT);
                }
                else if ($v == 'dev' || $v == 'pre') {
                    $currentUnstable = '_';
                    $vers[$k] = '_'.str_pad('', $pad, '0');
                }
                else {
                    $currentUnstable = strtolower(substr($v, 0, 1));
                    $vers[$k] = $currentUnstable.str_repeat('0', $pad);
                }
            }
            if ($serial) {
                $serial .= '-';
            }
            $serial .= implode('', $vers);
        }
        return $serial;
    }

    /**
     * Compare a version with a given range.
     * 
     * It does not compare with secondary version.
     * 
     * @param string|Version $version a version number
     * @param string|VersionRangeOperatorInterface $range   a version expression respecting Composer range syntax
     *
     * @return bool true if the given version match the given range
     */
    public static function compareVersionRange($version, $range)
    {
        if (is_object($range) && $range instanceof VersionRangeOperatorInterface) {
            $rangeStr = (string) $range;
        }
        else {
            $rangeStr = $range;
            $range = self::compileRange($range);
        }

        if ($rangeStr === '' || $version === '') {
            return true;
        }

        if (is_string($version)) {
            $v1 = Parser::parse($version);
        }
        else {
            $v1 = $version;
        }

        if ($v1->toString(true, false) === $rangeStr) {
            return true;
        }
        return $range->compare($v1);
    }

    /**
     * @return VersionRangeOperatorInterface
     */
    protected static function compileRange($range)
    {
        $or = preg_split('/\s*\|\|\s*/', $range, 2);
        if (count($or) > 1) {
            $left = self::compileRange($or[0]);
            $right = self::compileRange($or[1]);

            return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_OR, $left, $right);
        }
        $or = preg_split('/\s*\|\s*/', $range, 2);
        if (count($or) > 1) {
            $left = self::compileRange($or[0]);
            $right = self::compileRange($or[1]);

            return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_OR, $left, $right);
        }
        $and = preg_split("/\\s*,\\s*/", $range, 2);
        if (count($and) > 1) {
            $left = self::compileRange($and[0]);
            $right = self::compileRange($and[1]);

            return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
        }
        $and = preg_split("/(?<!-)\\s+(?!-)/", $range, 2);
        if (count($and) > 1) {
            $left = self::compileRange($and[0]);
            $right = self::compileRange($and[1]);

            return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
        }
        $between = preg_split("/\\s+\\-\\s+/", $range, 2);
        if (count($between) > 1) {
            // 1.0 - 2.0 is equivalent to >=1.0.0 <2.1
            // 1.0.0 - 2.1.0 is equivalent to >=1.0.0 <=2.1.0
            $v1 = Parser::parse($between[0]);
            $left = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_GTE, $v1);
            $v2 = Parser::parse($between[1]);
            if ($v2->hasPatch()) {
                $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LTE, $v2);
            } elseif ($v2->hasMinor()) {
                $v2 = $v2->getNextMinorVersion();
                $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v2);
            } else {
                $v2 = $v2->getNextMajorVersion();
                $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v2);
            }

            return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
        }
        $val = trim($range);
        if (preg_match("/^([\\!>=<~^]+)(.*)$/", $val, $m)) {
            $v1 = Parser::parse($m[2]);
            switch ($m[1]) {
                case '=':
                    $op = VersionRangeUnaryOperator::OP_EQ;
                    break;
                case '<':
                    $op = VersionRangeUnaryOperator::OP_LT;
                    break;
                case '>':
                    $op = VersionRangeUnaryOperator::OP_GT;
                    break;
                case '<=':
                    $op = VersionRangeUnaryOperator::OP_LTE;
                    break;
                case '>=':
                    $op = VersionRangeUnaryOperator::OP_GTE;
                    break;
                case '!=':
                    $op = VersionRangeUnaryOperator::OP_DIFF;
                    break;
                case '~':
                    // ~1.2 is equivalent to >=1.2 <2.0.0
                    // ~1.2.3 is equivalent to >=1.2.3 <1.3.0
                    if ($v1->hasPatch()) {
                        $v2 = Parser::parse($v1->getNextMinorVersion().'-dev');
                    } else {
                        $v2 = Parser::parse($v1->getNextMajorVersion().'-dev');
                    }
                    $left = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_GTE, $v1);
                    $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v2);

                    return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
                case '^':
                    // ^1.2.3 is equivalent to >=1.2.3 <3.0.0
                    // ^0.3    as >=0.3.0 <0.4.0
                    // ^0.3.2  as >=0.3.2 <0.4.0
                    $v2 = Parser::parse($v1->getNextMajorVersion().'-dev');
                    $left = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_GTE, $v1);
                    $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v2);

                    return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
                default:
                    throw new \Exception('Version comparator: bad operator in the range '.$range);
            }

            return new VersionRangeUnaryOperator($op, $v1);
        } elseif ($val === '*') {
            return new VersionRangeTrueOperator();
        }

        $v1 = Parser::parse($val);
        if (!$v1->hasWildcard()) {
            return new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_EQ, $v1);
        }
        

        if (preg_match('/^(.+)(\.\*)$/', $val, $m)) {
            return self::getRangeFromWildcardVersion($v1);
        }

        return new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_EQ, Parser::parse($range));
    }

    /**
     * @param Version $version
     * @return Version[]
     * @throws \Exception
     */
    protected static function getBoundsFromWildcardVersion(Version $version)
    {
        $versionArr = $version->getVersionArray();
        foreach ($versionArr as $k => $vNum) {
            if ($vNum === '*') {
                $versionArr[$k] = 0;
            }
        }

        $stability = $version->getStabilityVersion();
        if (!count($stability)) {
            $v2 = $version->getNextTailVersion();
            $v2 = new Version($v2->getVersionArray(), array('dev'),'', $v2->getSecondaryVersion());
            $v1 = new Version($versionArr, array('dev'),'', $version->getSecondaryVersion());
        } else {
            if ($version->hasWildcard() & $version::WILDCARD_ON_STABILITY_VERSION) {
                $majorStability = array();
                $foundWildcard = false;
                foreach ($stability as $k => $vNum) {
                    if ($vNum === '*') {
                        $stability[$k] = ($k ==0 ?'dev': 0);
                        $foundWildcard = true;
                    }
                    else if (!$foundWildcard) {
                        $majorStability[$k] = $vNum;
                    }
                }
                $v1 = new Version($versionArr, $stability, '', $version->getSecondaryVersion());
                $v2 = new Version($version->getVersionArray(), $majorStability,'', $version->getSecondaryVersion());
                $v2 = $v2->getNextStabilityVersion();
            }
            else if ($stability[0] !== '' && $stability[0] !== 'stable') {
                $v2 = new Version($version->getVersionArray(), array(), '', $version->getSecondaryVersion());
                $v2 = $v2->getNextTailVersion();
                $v2 = new Version($v2->getVersionArray(), array('dev'),'', $v2->getSecondaryVersion());
                $v1 = new Version($versionArr, $stability, '', $version->getSecondaryVersion());
            } else {
                $v1 = new Version($versionArr, array(), '', $version->getSecondaryVersion());
                $v2 = $version->getNextTailVersion();
            }
        }
        return array($v1, $v2);
    }

    /**
     * @param Version $version
     * @return VersionRangeOperatorInterface
     * @throws \Exception
     */
    public static function getRangeFromWildcardVersion(Version $version)
    {
        // 1.4.* -> >=1.4.0.0-dev <1.5.0.0-dev
        // 1.4.*-stable -> >=1.4.0.0 <1.5.0.0
        // 1.2.* ->  >= 1.2.0-dev <1.3.0-dev
        // 1.2.3.* ->  >= 1.2.3.0-dev <1.2.4-dev
        // 1.2.*-alpha.2', '>= 1.2.0-alpha.2 <1.3.0-dev
        list($v1, $v2) = self::getBoundsFromWildcardVersion($version);

        return self::getRangeOperatorFromBounds($v1, $v2);
    }

    protected static function getRangeOperatorFromBounds($v1, $v2)
    {
        $left = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_GTE, $v1);
        if ($v2->hasWildcard()) {
            $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LTE, $v2);
        }
        else {
            $right = new VersionRangeUnaryOperator(VersionRangeUnaryOperator::OP_LT, $v2);
        }

        return new VersionRangeBinaryOperator(VersionRangeBinaryOperator::OP_AND, $left, $right);
    }

}
