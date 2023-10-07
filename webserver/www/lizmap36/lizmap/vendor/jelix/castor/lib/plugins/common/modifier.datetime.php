<?php

/**
 * @author     Laurent Jouanneau
 * @contributor   Philippe Villiers
 *
 * @copyright   2012-2015 Laurent Jouanneau
 *
 * @link        http://www.jelix.org
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * modifier plugin : change the format of a date.
 *
 * The date can be given as a string, or as a DateTime object.
 *
 * It uses DateTime to convert a date. It takes two optionnal arguments.
 * The first one is the format of the output date. It should be a format understood by DateTime,
 * By default, it uses 'Y-m-d'.
 * The second one is the format of the given date, if the date format is not understood by DateTime.
 *
 * examples :
 *  {$mydate|datetime}
 *  {$mydate|datetime:'d/m/Y'}
 *
 * @param string $date the date
 * @param string $format_in  the format identifier of the given date
 * @param string $format_out the format identifier of the output date
 *
 * @return string the converted date
 *
 * @see jDateTime
 */
function jtpl_modifier_common_datetime($date, $format_out = 'Y-m-d', $format_in = '')
{
    if (!($date instanceof DateTime)) {
        if ($date == '' || $date == '0000/00/00') {
            return '';
        }
        if ($format_in) {
            $date = date_create_from_format($format_in, $date);
        } else {
            $date = new DateTime($date);
        }
        if (!$date) {
            return '';
        }
    }

    return $date->format($format_out);
}
