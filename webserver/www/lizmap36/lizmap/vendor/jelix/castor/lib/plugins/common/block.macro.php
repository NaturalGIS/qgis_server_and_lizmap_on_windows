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
 * @param \Jelix\Castor\CompilerCore $compiler
 * @param bool $begin
 * @param array $params  the first value must represent a PHP string, others values must be a variable name
 *          0 => macro name
 *          1... => parameters names
 * @return string
 */

function jtpl_block_common_macro($compiler, $begin, $params = array())
{

    if (!$begin) {
        return "\n});\n";
    }
    if (count($params) < 1) {
        $compiler->doError2('errors.tplplugin.block.bad.argument.number', 'macro', ">=1");
    }

    if (!preg_match('/^([\'\"])[a-z0-9_]+([\'\"])$/i', $params[0], $m)) {
        $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', $params[0]);
    }

    if ($m[1] != $m[2]) {
        // there is no same quote at the begin and at the end
        $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', $params[0]);
    }

    $blockName = $params[0];
    array_shift($params);
    $parametersNames = array();
    foreach($params as $k => $param) {
        if (!preg_match('/^\s*\\$t->_vars\\[\'([a-z0-9_]+)\']\s*$/i', $param, $m)) {
            $compiler->doError2('errors.tpl.tag.phpsyntax.invalid', 'macro', '#'.$param.'#');
        }
        $parametersNames[] = '\''.$m[1].'\'';
    }

    $content = '$t->declareMacro('.$blockName.', array('.implode(',', $parametersNames).'), '.
    ' function($t) {'."\n";

    return $content;
}