<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2008-2020 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     MIT
 */


namespace Jelix\Version;

class VersionRangeTrueOperator implements VersionRangeOperatorInterface
{
    public function compare(Version $value)
    {
        return true;
    }

    function __toString()
    {
        return 'true';
    }
}
