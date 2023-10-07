<?php
/**
 * @author      Laurent Jouanneau
 *
 * @copyright   2022 Laurent Jouanneau
 *
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * Displays the macro indicated as first parameter of the tag.
 *
 * Example: `{usemacro 'mymacro'}`
 * If the macro accepts some parameters, you can give them:
 * `{usemacro 'mymacro', $arg1, 'arg2'}`
 *
 * A macro should be declared with `{macro}`
 *
 * @param \Jelix\Castor\CastorCore $tpl
 */
function jtpl_function_common_usemacro($tpl, $blockName, ...$parameters)
{
    $tpl->callMacro($blockName, $parameters);
}