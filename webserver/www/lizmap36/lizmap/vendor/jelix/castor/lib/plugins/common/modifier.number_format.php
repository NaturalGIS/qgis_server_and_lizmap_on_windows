<?php

/**
 * Castor plugin that wraps PHP number_format function.
 *
 * @author     Julien Issler
 * @contributor Mickael Fradin, Laurent Jouanneau
 *
 * @copyright  2008-2010 Julien Issler, 2009 Mickael Fradin, 2015 Laurent Jouanneau
 *
 * @link       http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * NumberFormat plugin for Castor that wraps PHP number_format function.
 *
 * @param float $number the number to format
 * @param int $decimals the number of decimals to return
 * @param string $dec_point the separator string for the decimals
 * @param string $thousands_sep the separator string for the thousands
 *
 * @return string
 */
function jtpl_modifier_common_number_format($number, $decimals = 0, $dec_point = false, $thousands_sep = false)
{
    if ($dec_point === false && $thousands_sep === false) {
        $number = number_format($number, $decimals);
    } else {
        $number = number_format($number, $decimals, ($dec_point === false ? '.' : $dec_point), ($thousands_sep === false ? ',' : $thousands_sep));
    }

    return $number;
}
