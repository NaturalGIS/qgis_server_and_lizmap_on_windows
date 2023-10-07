<?php

/**
 * Modifier Plugin.
 *
 * @author Laurent Jouanneau
 *
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * modifier plugin :  apply the implode function on the given value.
 *
 * @param array $arr the array of strings
 * @param string $glue the glue
 *
 * @return string
 */
function jtpl_modifier_common_implode($arr, $glue = ' ')
{
    return implode($glue, $arr);
}
