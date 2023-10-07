<?php
/**
* @author      Laurent Jouanneau
* @copyright   2016-2020 Laurent Jouanneau
*
* @link        http://www.jelix.org
* @licence     MIT
*/

namespace Jelix\Version;

/**
 * Embed version informations.
 */
class Version
{
    /**
     * @var int[] list of numbers of the version  (ex: [1,2,3] for 1.2.3)
     */
    private $version = array();

    /**
     * @var string[] list of stability informations  (ex: ['alpha', '2'] for 1.2.3-alpha.2)
     */
    private $stabilityVersion = array();

    /**
     * @var string any informations that are after a '+' in a semantic version
     */
    private $buildMetadata = '';

    /**
     * @var Version|null secondary version, i.e. a version after a ':' or a '-'
     */
    private $secondaryVersion = null;

    private $secondaryVersionSeparator = '-';

    private $wildcard = 0;

    const WILDCARD_ON_VERSION = 1;

    const WILDCARD_ON_STABILITY_VERSION = 2;

    /**
     * @param int[]    $version          list of numbers of the version
     *                                   (ex: [1,2,3] for 1.2.3)
     * @param string[] $stabilityVersion list of stability informations
     *                                   that are informations following a '-' in a semantic version
     *                                   (ex: ['alpha', '2'] for 1.2.3-alpha.2)
     * @param string  build metadata  the metadata, informations that
     *  are after a '+' in a semantic version
     *     (ex: 'build-56458' for 1.2.3-alpha.2+build-56458)
     * 
     * @param Version|null $secondaryVersion secondary version, i.e. a version after a ':'
     */
    public function __construct(array $version,
                                array $stabilityVersion = array(),
                                $buildMetadata = '',
                                $secondaryVersion = null,
                                $secondaryVersionSeparator = '-')
    {
        $this->version = count($version) ? $version: array(0);
        $this->stabilityVersion = $stabilityVersion;

        if (in_array('*', $this->stabilityVersion, true)) {
            $this->wildcard |= self::WILDCARD_ON_STABILITY_VERSION;
        }
        if (in_array('*', $this->version, true)) {
            $this->wildcard |= self::WILDCARD_ON_VERSION;
        }
        $this->buildMetadata = $buildMetadata;
        $this->secondaryVersion = $secondaryVersion;
        $this->secondaryVersionSeparator = $secondaryVersionSeparator;
    }

    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @param bool $withPatch true, it returns always x.y.z even
     *                        if no patch or minor version was given
     * @param bool $withSecondaryVersion set to false to not include secondary version
     */
    public function toString($withPatch = true, $withSecondaryVersion = true)
    {
        $version = $this->version;
        if (!($this->wildcard & self::WILDCARD_ON_VERSION) && $withPatch && count($version) < 3) {
            $version = array_pad($version, 3, 0);
        }


        if ($this->stabilityVersion) {
            $stability = '-'.implode('.', $this->stabilityVersion);
        }
        else {
            $stability = '';
        }

        if ($this->secondaryVersion && $withSecondaryVersion) {
            $secondary = $this->secondaryVersion->toString();

            if ($stability == '-*' && $secondary == '*') {
                $secondary = '';
            }
            else {
                $secondary = $this->secondaryVersionSeparator.$secondary;
            }
        }
        else {
            $secondary = '';
        }

        $vers = implode('.', $version).$stability.$secondary;

        if ($this->buildMetadata) {
            $vers .= '+'.$this->buildMetadata;
        }

        return $vers;
    }

    /**
     * @return integer 0 if there is no wildcard, else one of the constant WILDCARD_ON_*
     */
    public function hasWildcard () {
        return $this->wildcard;
    }

    public function getMajor()
    {
        return $this->version[0];
    }

    public function hasMinor()
    {
        return isset($this->version[1]);
    }

    public function getMinor()
    {
        if (isset($this->version[1])) {
            return $this->version[1];
        }
        if ($this->version[0] === '*') {
            return '*';
        }
        return 0;
    }

    public function hasPatch()
    {
        return isset($this->version[2]);
    }

    public function getPatch()
    {
        if (isset($this->version[2])) {
            return $this->version[2];
        }
        if ($this->getMinor() === '*') {
            return '*';
        }
        return 0;
    }

    public function getTailNumbers()
    {
        if (count($this->version) > 3) {
            return array_slice($this->version, 3);
        }

        return array();
    }

    public function getVersionArray()
    {
        return $this->version;
    }

    public function getBranchVersion()
    {
        return $this->version[0].'.'.$this->getMinor();
    }

    public function getStabilityVersion()
    {
        return $this->stabilityVersion;
    }

    public function getBuildMetadata()
    {
        return $this->buildMetadata;
    }

    /**
     * @return Version|null
     */
    public function getSecondaryVersion() {
        return $this->secondaryVersion;
    }

    public function getSecondaryVersionSeparator() {
        return $this->secondaryVersionSeparator;
    }

    /**
     * Returns the next major version
     * 2.1.3 -> 3.0.0
     * 2.1b1.4 -> 3.0.0.
     *
     * @return Version the next version
     */
    public function getNextMajorVersion($ignoreStability = true)
    {
        if ($this->version[0] === '*') {
            return new Version(array('*'));
        }
        if (!$ignoreStability && count($this->stabilityVersion) && $this->stabilityVersion[0] != 'stable') {
            return new Version($this->version);
        }
        return new Version(array(($this->version[0] + 1), 0));
    }

    /**
     * Returns the next minor version
     * 2.1.3 -> 2.2.0
     * 2.1 -> 2.2.0
     * 2.1b1.4 -> 2.1.0
     *
     * @return Version the next version
     */
    public function getNextMinorVersion($ignoreStability = true)
    {
        if ($this->getMinor() === '*') {
            return new Version(array('*'));
        }

        if (!$ignoreStability && count($this->stabilityVersion) && $this->stabilityVersion[0] != 'stable') {
            return new Version($this->version);
        }

        return new Version(array($this->version[0],($this->getMinor() + 1)));
    }

    /**
     * Returns the next patch version
     * 2.1.3 -> 2.1.4
     * 2.1b1.4 -> 2.1.0
     *
     * @return Version the next version
     */
    public function getNextPatchVersion($ignoreStability = true)
    {
        if ($this->getPatch() === '*') {
            return new Version(array('*'));
        }
        if (!$ignoreStability && count($this->stabilityVersion) && $this->stabilityVersion[0] != 'stable') {
            return new Version($this->version);
        }
        return new Version(array($this->version[0], $this->getMinor(), ($this->getPatch() + 1)));
    }

    /**
     * returns the next version, by incrementing the last
     * number, whatever it is.
     * If the version has a stability information (alpha, beta etc..),
     * it returns only the version without stability version.
     *
     * @return Version the next version
     */
    public function getNextTailVersion($ignoreStability = false)
    {
        if (!$ignoreStability && count($this->stabilityVersion) && $this->stabilityVersion[0] != 'stable') {
            return new Version($this->version);
        }

        $v = $this->version;
        $last = count($v) - 1;
        if ($v[$last] == '*' && $last >= 1) {
            $v = array_slice($v, 0, $last);
            $last --;
        }
        $v[$last]++;
        return new Version($v);
    }

    /**
     * Returns the version having the next major stability version
     *
     * @return Version
     */
    public function getNextStabilityVersion()
    {
        if (!count($this->stabilityVersion) || $this->stabilityVersion[0] == 'stable') {
            if (count($this->version) > 3) {
                $v = $this->getNextTailVersion();
            }
            else {
                $v = $this->getNextPatchVersion();
            }
            return new Version($v->getVersionArray(), array('dev'));
        }
        $stability = $this->stabilityVersion[0];
        if ($stability === 'dev' || $stability === 'pre') {
            $stability = 'alpha';
        }
        else if ($stability === 'alpha') {
            $stability = 'beta';
        }
        else if ($stability === 'beta') {
            $stability = 'rc';
        }
        else if ($stability === 'rc') {
            return new Version($this->version);
        }
        else if (is_numeric($stability)) {
            $stability ++;
        }
        return new Version($this->version, array($stability));
    }

    /**
     * Returns the version having the next tail stability version
     *
     * @return Version
     */
    public function getNextTailStabilityVersion()
    {
        if (!count($this->stabilityVersion) || $this->stabilityVersion[0] === 'stable') {
            if (count($this->version) > 3) {
                $v = $this->getNextTailVersion();
            }
            else {
                $v = $this->getNextPatchVersion();
            }
            return new Version($v->getVersionArray(), array('dev'));
        }

        $last = count($this->stabilityVersion) - 1;
        $stability = $this->stabilityVersion[$last];
        if ($stability === 'dev' || $stability === 'pre') {
            $stability = 'alpha';
        }
        else if ($stability === 'alpha') {
            $stability = 'beta';
        }
        else if ($stability === 'beta') {
            $stability = 'rc';
        }
        else if ($stability === 'rc') {
            return new Version($this->version);
        }
        else if (is_numeric($stability)) {
            $stability ++;
        }

        $stabVer = $this->stabilityVersion;
        $stabVer[$last] = $stability;
        return new Version($this->version, $stabVer);
    }
}
