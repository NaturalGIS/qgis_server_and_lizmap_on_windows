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
 * a special if block to test if a macro does not exist
 *
 * <pre>{ifundefinedmacro 'themacro'} here generated content if the macro does not exists {include 'mymacro.tpl'}   {/ifundefinedmacro}</pre>
 *
 * @param \Jelix\Castor\CompilerCore $compiler the template compiler
 * @param bool         $begin    true if it is the begin of block, else false
 * @param array        $params    0 => macro name
 *
 * @return string the php code corresponding to the begin or end of the block
 */
function jtpl_block_common_ifundefinedmacro($compiler, $begin, $params = array())
{
    if ($begin) {
        if (count($params) != 1) {
            $content = '';
            $compiler->doError2('errors.tplplugin.block.bad.argument.number', 'ifundefinedmacro', '1');
        } else {
            $content = ' if(!$t->isMacroDefined('.$params[0].')) {';
        }
    } else {
        $content = ' } ';
    }

    return $content;
}
