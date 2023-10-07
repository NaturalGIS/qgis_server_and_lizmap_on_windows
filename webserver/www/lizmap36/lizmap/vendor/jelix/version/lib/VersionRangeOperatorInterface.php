<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2008-2020 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence     MIT
 */

namespace Jelix\Version;

interface VersionRangeOperatorInterface
{
    /**
    * @return bool
    */
    public function compare(Version $value);
}
