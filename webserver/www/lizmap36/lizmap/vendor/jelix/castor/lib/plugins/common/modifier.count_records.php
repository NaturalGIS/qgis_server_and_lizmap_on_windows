<?php

/**
 * Plugin to display count of a DbResultSet Object (how many records).
 *
 * @author     Dandelionmood
 * @copyright  2007 Dandelionmood
 *
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * modifier plugin :  display count of a DbResultSet Object (how many records).
 *
 * <pre>{$myarray|count_records}</pre>
 *
 * @param jDbResultSet $DbResultSet
 *
 * @return int
 */
function jtpl_modifier_common_count_records($DbResultSet)
{
    return $DbResultSet->rowCount();
}
