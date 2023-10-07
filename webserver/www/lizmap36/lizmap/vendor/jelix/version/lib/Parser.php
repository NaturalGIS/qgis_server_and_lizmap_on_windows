<?php
/**
* @author      Laurent Jouanneau
* @copyright   2016-2018 Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     MIT
*/

namespace Jelix\Version;

class Parser
{
    private function __construct()
    {
    }

    /**
     * Is able to parse semantic version syntax or any other version syntax.
     *
     * @param string $version
     * @param array $options list of options for the parser.
     *          'removeWildcard' => false
     * 
     * @return Version
     */
    public static function parse($version, $options = array())
    {
        if ($version instanceof Version) {
            // for backward compatibility
            return clone $version;
        }
        $options = array_merge(array(
            'removeWildcard' => false
        ), $options);

        // extract meta data
        $vers = explode('+', $version, 2);
        $metadata = '';
        if (count($vers) > 1) {
            $metadata = $vers[1];
        }
        $version = $vers[0];

        // extract secondary version
        $allVersions = preg_split('/(-|:)([0-9]+|\\*)($|\\.|-)/', $version, 2, PREG_SPLIT_DELIM_CAPTURE);
        $version = $allVersions[0];
        $stabilityVersion = array();

        // extract stability part
        $vers = explode('-', $version, 2);
        if (count($vers) > 1) {
            $stabilityVersion = explode('.', $vers[1]);
        }

        // extract version parts
        $versionHasWildcard = false;
        $vers = explode('.', $vers[0]);
        foreach ($vers as $k => $number) {
            if (!is_numeric($number)) {
                if (preg_match('/^([0-9]+)([a-zA-Z]+|\\*)([0-9]*|\\*?)(.*)$/', $number, $m)) {
                    // we got a number like '8a2', '5beta4', '3alpha*' ..
                    // so it defines a stability version
                    $vers[$k] = intval($m[1]);
                    if ($m[2] === '*') {
                        if ((isset($m[3]) && $m[3] !== '') || (isset($m[4]) && $m[4] !== '')) {
                            throw new \Exception('Bad version syntax on "'.$version.'"');
                        }
                        $vers = array_slice($vers, 0, $k+1);
                        $versionHasWildcard = true;
                        if (!$options['removeWildcard']) {
                            $vers[$k+1] = '*';
                        }
                        break;
                    }
                    $sv = array($m[2]);
                    if (isset($m[3]) && $m[3] !== '') {
                        $sv[] = intval($m[3]);
                    }
                    if (isset($m[4]) && $m[4] !== '') {
                        $sv[] = $m[4];
                    }
                    $stabilityVersion = array_merge(
                                            $sv,
                                            array_slice($vers, $k + 1),
                                            $stabilityVersion
                                        );
                    $vers = array_slice($vers, 0, $k + 1);
                    break;
                } elseif ($number == '*') {
                    $versionHasWildcard = true;
                    $vers = array_slice($vers, 0, $k);
                    if (!$options['removeWildcard']) {
                        $vers[$k] = '*';
                    }
                    break;
                } else {
                    throw new \Exception('Bad version syntax on "'.$version.'"');
                }
            } else {
                if ($versionHasWildcard) {
                    throw new \Exception('Bad version syntax on "'.$version.'", wildcard should be the last part.');
                }
                $vers[$k] = intval($number);
            }
        }

        $stab = array();
        foreach ($stabilityVersion as $k => $part) {
            if (preg_match('/^[a-z]+$/', $part)) {
                $stab[] = self::normalizeStability($part);
            } elseif (preg_match('/^[0-9]+$/', $part)) {
                $stab[] = intval($part);
            } else {
                $m = preg_split('/([0-9]+)/', $part, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
                foreach ($m as $p) {
                    $stab[] = self::normalizeStability($p);
                }
            }
        }
        if ($versionHasWildcard && (count($stab) > 1 || (count($stab)==1 && $stab[0] != 'stable')) ) {
            throw new \Exception('Bad version syntax on "'.$version.'", wildcard should be the last part.');
        }

        if (count($allVersions) > 1 && $allVersions[1] != '') {
            if ($allVersions[2] == '*') {

                if ($options['removeWildcard']) {
                    $secondaryVersion = null;
                    $secondaryVersionSeparator = '-';
                }
                else {
                    if ($allVersions[1] == '-'  && count($stab) == 0) {
                        $stab = array('*');
                        $secondaryVersionSeparator = ':';
                        $secondaryVersion = null;
                    }
                    else {
                        $secondaryVersionSeparator = $allVersions[1];
                        $secondaryVersion = new Version(array('*'));
                    }

                }
            }
            else {
                $secondaryVersion = self::parse($allVersions[2].$allVersions[3].$allVersions[4], $options);
                $secondaryVersionSeparator = $allVersions[1];
            }
        }
        else {
            $secondaryVersion = null;
            $secondaryVersionSeparator = '-';
        }

        return new Version($vers, $stab, $metadata, $secondaryVersion, $secondaryVersionSeparator);
    }

    protected static function normalizeStability($stab)
    {
        $stab = strtolower($stab);
        if ($stab == 'a') {
            $stab = 'alpha';
        }
        if ($stab == 'b') {
            $stab = 'beta';
        }

        return $stab;
    }
}
