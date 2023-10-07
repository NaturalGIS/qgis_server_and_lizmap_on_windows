<?php

/**
 * @author      Laurent Jouanneau
 * @contributor Loic Mathaud (standalone version), Dominique Papin, DSDenes, Christophe Thiriot, Julien Issler, Brice Tence
 *
 * @copyright   2005-2022 Laurent Jouanneau
 * @copyright   2006 Loic Mathaud, 2007 Dominique Papin, 2009 DSDenes, 2010 Christophe Thiriot
 * @copyright   2010-2016 Julien Issler, 2010 Brice Tence
 *
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

namespace Jelix\Castor;

/**
 * This is the compiler of templates: it converts a template into a php file.
 */
abstract class CompilerCore
{
    protected $_literals;
    protected $_verbatims;

    /**
     * tokens of variable type.
     */
    protected $_vartype = array(T_CONSTANT_ENCAPSED_STRING, T_DNUMBER,
            T_ENCAPSED_AND_WHITESPACE, T_LNUMBER, T_OBJECT_OPERATOR, T_STRING,
            T_WHITESPACE, T_ARRAY, );

    /**
     * tokens of operators for assignements.
     */
    protected $_assignOp = array(T_AND_EQUAL, T_DIV_EQUAL, T_MINUS_EQUAL,
            T_MOD_EQUAL, T_MUL_EQUAL, T_OR_EQUAL, T_PLUS_EQUAL,
            T_SL_EQUAL, T_SR_EQUAL, T_XOR_EQUAL, );

    /**
     * tokens of operators for tests.
     */
    protected $_op = array(T_BOOLEAN_AND, T_BOOLEAN_OR, T_EMPTY, T_INC, T_DEC,
            T_ISSET, T_IS_EQUAL, T_IS_GREATER_OR_EQUAL, T_IS_IDENTICAL,
            T_IS_NOT_EQUAL, T_IS_NOT_IDENTICAL, T_IS_SMALLER_OR_EQUAL,
            T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR, T_SR, T_SL,
            T_DOUBLE_ARROW, );

    /**
     * tokens authorized into locale names.
     */
    protected $_inLocaleOk = array(T_STRING, T_ABSTRACT, T_AS, T_BREAK, T_CASE,
            T_CATCH, T_CLASS, T_CLONE, T_CONST, T_CONTINUE, T_DECLARE, T_DEFAULT,
            T_DNUMBER, T_DO, T_ECHO, T_ELSE, T_ELSEIF, T_EMPTY, T_ENDIF, T_ENDFOR,
            T_EVAL, T_EXIT, T_EXTENDS, T_FINAL, T_FOR, T_FOREACH, T_FUNCTION,
            T_GLOBAL, T_GOTO, T_IF, T_IMPLEMENTS, T_INCLUDE, T_INSTANCEOF, T_INTERFACE,
            T_LIST, T_LNUMBER, T_LOGICAL_AND, T_LOGICAL_OR, T_LOGICAL_XOR,
            T_NAMESPACE, T_NEW, T_PRINT, T_PRIVATE, T_PUBLIC, T_PROTECTED, T_REQUIRE,
            T_RETURN, T_STATIC, T_SWITCH, T_THROW, T_TRY, T_USE, T_VAR, T_WHILE, );

    /**
     * tokens allowed in output for variables.
     */
    protected $_allowedInVar;

    /**
     * tokens allowed into expressions.
     */
    protected $_allowedInExpr;

    /**
     * tokens allowed into assignements.
     */
    protected $_allowedAssign;

    /**
     * tokens allowed in foreach statements.
     */
    protected $_allowedInForeach;

    /**
     * tokens not allowed in variable.
     */
    protected $_excludedInVar = array(';','=');

    protected $_allowedConstants = array('TRUE','FALSE','NULL', 'M_1_PI',
            'M_2_PI', 'M_2_SQRTPI', 'M_E', 'M_LN10', 'M_LN2', 'M_LOG10E',
            'M_LOG2E', 'M_PI','M_PI_2','M_PI_4','M_SQRT1_2','M_SQRT2', );

    /**
     * list of plugins paths.
     */
    protected $_pluginPath = array();

    protected $_metaBody = '';

    /**
     * native modifiers.
     */
    protected $_modifier = array('upper' => 'strtoupper', 'lower' => 'strtolower',
            'strip_tags' => 'strip_tags', 'escurl' => 'rawurlencode',
            'capitalize' => 'ucwords', 'stripslashes' => 'stripslashes',
            'upperfirst' => 'ucfirst', 'json_encode'=>'json_encode');

    /**
     * stack of founded blocks.
     */
    protected $_blockStack = array();

    /**
     * name of the template file.
     */
    protected $_sourceFile;

    /**
     * current parsed jtpl tag.
     */
    protected $_currentTag;

    /**
     * type of the output.
     */
    public $outputType = '';

    /**
     * true if the template doesn't come from an untrusted source.
     * if it comes from an untrusted source, like a template uploaded by a user,
     * you should set to false.
     */
    public $trusted = true;


    protected $encoding = 'UTF-8';

    protected $_autoescape = false;

    /**
     * list of user functions.
     */
    protected $_userFunctions = array();

    protected $removeASPtags = true;

    /**
     * Initialize some properties.
     */
    public function __construct($encoding = 'UTF-8')
    {
        $this->encoding = $encoding;

        if (defined('T_CHARACTER')) {
            $this->_vartype[] = T_CHARACTER;
        }
        $this->_allowedInVar = array_merge($this->_vartype, array(T_INC, T_DEC, T_DOUBLE_ARROW));

        if (defined('T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG')) {
            $this->_allowedInVar[] = T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG;
            $this->_op[] = T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG;
        }
        $this->_allowedInExpr = array_merge($this->_vartype, $this->_op);
        $this->_allowedAssign = array_merge($this->_vartype, $this->_assignOp, $this->_op);
        $this->_allowedInForeach = array_merge($this->_vartype, array(T_AS, T_DOUBLE_ARROW));

        $this->removeASPtags = (ini_get('asp_tags') == '1');
    }

    /**
     * Enable or disable the autoescaping behavior
     * @param boolean $enabled
     * @return void
     */
    public function setAutoEscape($enabled = true)
    {
        $this->_autoescape = $enabled;
    }

    public function isAutoEscapeEnabled()
    {
        return $this->_autoescape;
    }


    public function compileString($templateContent, $cacheFile, $userModifiers, $userFunctions, $md5, $header = '', $footer = '')
    {
        $this->_modifier = array_merge($this->_modifier, $userModifiers);
        $this->_userFunctions = $userFunctions;

        $result = $this->compileContent($templateContent);

        $header = "<?php \n".$header;
        foreach ($this->_pluginPath as $path => $ok) {
            $header .= ' require_once(\''.$path."');\n";
        }
        $header .= 'function template_meta_'.$md5.'($t){';
        $header .= "\n".$this->_metaBody."\n}\n";

        $header .= 'function template_'.$md5.'($t){'."\n?>";
        $result = $header.$result."<?php \n}\n".$footer;

        $this->_saveCompiledString($cacheFile, $result);

        return true;
    }

    abstract protected function _saveCompiledString($cacheFile, $result);

    protected function compileContent($tplContent)
    {
        $this->_metaBody = '';
        $this->_blockStack = array();

        // we remove all php tags
        $tplContent = preg_replace("!<\?((?:php|=|\s).*)\?>!s", '', $tplContent);
        // we remove all template comments
        $tplContent = preg_replace("!{\*(.*?)\*}!s", '', $tplContent);
        $tplContent = preg_replace("!{#(.*?)#}!s", '', $tplContent);

        $tplContent = preg_replace_callback("!(<\?.*\?>)!sm", function ($matches) {
            return '<?php echo \''.str_replace("'", "\\'", $matches[1]).'\'?>';
        }, $tplContent);

        if ($this->removeASPtags) {
            // we remove all asp tags
          $tplContent = preg_replace('!<%.*%>!s', '', $tplContent);
        }

        preg_match_all('!{literal}(.*?){/literal}!s', $tplContent, $_match);
        $this->_literals = $_match[1];
        $tplContent = preg_replace('!{literal}(.*?){/literal}!s', '{literal}', $tplContent);

        preg_match_all('!{verbatim}(.*?){/verbatim}!s', $tplContent, $_match);
        $this->_verbatims = $_match[1];
        $tplContent = preg_replace('!{verbatim}(.*?){/verbatim}!s', '{verbatim}', $tplContent);

        $tplContent = preg_replace_callback("/{((.).*?)}(\n)/sm", function ($matches) {
                list($full, , $firstCar, $lastcar) = $matches;
                if ($firstCar == '=' || $firstCar == '$' || $firstCar == '@') {
                    return "$full\n";
                } else {
                    return $full;
                }
            }, $tplContent);
        $tplContent = preg_replace_callback('/{((.).*?)}/sm', array($this, '_callback'), $tplContent);

        $tplContent = preg_replace('/<\?php\\s+\?>/', '', $tplContent);

        if (count($this->_blockStack)) {
            $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
        }

        return $tplContent;
    }

    /**
     * function called during the parsing of the template by a preg_replace_callback function
     * It is called on each template tag {xxxx }.
     *
     * @param array $matches a matched item
     *
     * @return string the corresponding php code of the tag (with php tag).
     */
    public function _callback($matches)
    {
        list(, $tag, $firstcar) = $matches;

        // check the first character
        if (!preg_match('/^\$|@|=|[a-zA-Z\/]|!$/', $firstcar)) {
            $this->doError1('errors.tpl.tag.syntax.invalid', $tag);
        }

        $this->_currentTag = $tag;
        if ($firstcar == '=') {
            return  '<?php echo '.$this->_parseVariable(substr($tag, 1)).'; ?>';
        } elseif ($firstcar == '$' || $firstcar == '@') {
            return '<?php echo ' . $this->_parseVariable($tag) . '; ?>';
        } elseif ($firstcar == '!') {
            return $this->_parsePragma($tag);
        } else {
            if (!preg_match('/^(\/?[a-zA-Z0-9_]+)(?:(?:\s+(.*))|(?:\((.*)\)))?$/ms', $tag, $m)) {
                $this->doError1('errors.tpl.tag.function.invalid', $tag);
            }
            if (count($m) == 4) {
                $m[2] = $m[3];
            }
            if (!isset($m[2])) {
                $m[2] = '';
            }
            if ($m[1] == 'ldelim') {
                return '{';
            }
            if ($m[1] == 'rdelim') {
                return '}';
            }

            return '<?php '.$this->_parseFunction($m[1], $m[2]).'?>';
        }
    }

    /**
     * analyse an "echo" tag : {$..} or {@..}.
     *
     * @param string $expr the content of the tag
     *
     * @return string the corresponding php instruction
     */
    protected function _parseVariable($expr)
    {
        $tok = explode('|', $expr);
        $res = $this->_parseFinal(array_shift($tok), $this->_allowedInVar, $this->_excludedInVar);

        $hasEscHtmlModifier = false;
        $hasNoEscModifier = false;
        foreach ($tok as $modifier) {
            if (!preg_match('/^(\w+)(?:\:(.*))?$/', $modifier, $m)) {
                $this->doError2('errors.tpl.tag.modifier.invalid', $this->_currentTag, $modifier);
            }

            if (isset($m[2])) {
                $targs = $this->_parseFinal($m[2], $this->_allowedInVar, $this->_excludedInVar, true, ',', ':');
                array_unshift($targs, $res);
            } else {
                $targs = array($res);
            }

            if ($path = $this->_getPlugin('cmodifier', $m[1])) {
                require_once $path[0];
                $fct = $path[1];
                $res = $fct($this, $targs);
            } elseif ($path = $this->_getPlugin('modifier2', $m[1])) {
                $res = $path[1].'($t, '.implode(',', $targs).')';
                $this->_pluginPath[$path[0]] = true;
            } elseif ($path = $this->_getPlugin('modifier', $m[1])) {
                $res = $path[1].'('.implode(',', $targs).')';
                $this->_pluginPath[$path[0]] = true;
            } else {
                if ($m[1] == 'noesc' || $m[1] == 'raw') {
                    $hasNoEscModifier = true;
                }
                elseif ($m[1] == 'eschtml' || $m[1] == 'escxml') {
                    $hasEscHtmlModifier = true;
                    $res = 'htmlspecialchars('.$res.', ENT_QUOTES | ENT_SUBSTITUTE, "'.$this->encoding.'")';
                }
                elseif (isset($this->_modifier[$m[1]])) {
                    $res = $this->_modifier[$m[1]].'('.$res.')';
                } else {
                    $this->doError2('errors.tpl.tag.modifier.unknown', $this->_currentTag, $m[1]);
                }
            }
        }

        if ($this->_autoescape && !$hasEscHtmlModifier && !$hasNoEscModifier) {
            switch($this->outputType) {
                case 'html':
                case 'xml':
                case '':
                    $res = 'htmlspecialchars('.$res.', ENT_QUOTES | ENT_SUBSTITUTE, "'.$this->encoding.'")';
                    break;
            }

        }

        return $res;
    }

    /**
     * analyse the tag which have a name.
     *
     * @param string $name the name of the tag
     * @param string $args the content that follow the name in the tag
     *
     * @return string the corresponding php instructions
     */
    protected function _parseFunction($name, $args)
    {
        $res = '';
        switch ($name) {
            case 'if':
                $res = 'if('.$this->_parseFinal($args, $this->_allowedInExpr).'):';
                array_push($this->_blockStack, 'if');
                break;

            case 'else':
                if (substr(end($this->_blockStack), 0, 2) != 'if') {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    $res = 'else:';
                }
                break;

            case 'elseif':
                if (end($this->_blockStack) != 'if') {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    $res = 'elseif('.$this->_parseFinal($args, $this->_allowedInExpr).'):';
                }
                break;

            case 'foreach':
                if ($this->trusted) {
                    $notallowed = array(';','!');
                } else {
                    $notallowed = array(';','!','(');
                }

                if (preg_match("/^\s*\((.*)\)\s*$/", $args, $m)) {
                    $args = $m[1];
                }

                $res = 'foreach('.$this->_parseFinal($args, $this->_allowedInForeach, $notallowed).'):';
                array_push($this->_blockStack, 'foreach');
                break;

            case 'while':
                $res = 'while('.$this->_parseFinal($args, $this->_allowedInExpr).'):';
                array_push($this->_blockStack, 'while');
                break;

            case 'for':
                if ($this->trusted) {
                    $notallowed = array();
                } else {
                    $notallowed = array('(');
                }
                if (preg_match("/^\s*\((.*)\)\s*$/", $args, $m)) {
                    $args = $m[1];
                }
                $res = 'for('.$this->_parseFinal($args, $this->_allowedInExpr, $notallowed).'):';
                array_push($this->_blockStack, 'for');
                break;

            case '/foreach':
            case '/for':
            case '/if':
            case '/while':
                $short = substr($name, 1);
                if (end($this->_blockStack) != $short) {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    array_pop($this->_blockStack);
                    $res = 'end'.$short.';';
                }
                break;

            case 'assign':
            case 'set':
            case 'eval':
                $res = $this->_parseFinal($args, $this->_allowedAssign).';';
                break;

            case 'literal':
                if (count($this->_literals)) {
                    $res = '?>'.array_shift($this->_literals).'<?php ';
                } else {
                    $this->doError1('errors.tpl.tag.block.end.missing', 'literal');
                }
                break;

            case 'verbatim':
                if (count($this->_verbatims)) {
                    $res = '?>'.array_shift($this->_verbatims).'<?php ';
                } else {
                    $this->doError1('errors.tpl.tag.block.end.missing', 'verbatim');
                }
                break;

            case '/literal':
            case '/verbatim':
                $this->doError1('errors.tpl.tag.block.begin.missing', substr($name, 1));
                break;

            case 'meta':
                $this->_parseMeta($args);
                break;

            case 'meta_if':
                $metaIfArgs = $this->_parseFinal($args, $this->_allowedInExpr);
                $this->_metaBody .= 'if('.$metaIfArgs.'):'."\n";
                array_push($this->_blockStack, 'meta_if');
                break;

            case 'meta_else':
                if (substr(end($this->_blockStack), 0, 7) != 'meta_if') {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    $this->_metaBody .= "else:\n";
                }
                break;

            case 'meta_elseif':
                if (end($this->_blockStack) != 'meta_if') {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    $elseIfArgs = $this->_parseFinal($args, $this->_allowedInExpr);
                    $this->_metaBody .= 'elseif('.$elseIfArgs."):\n";
                }
                break;

            case '/meta_if':
                $short = substr($name, 1);
                if (end($this->_blockStack) != $short) {
                    $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                } else {
                    array_pop($this->_blockStack);
                    $this->_metaBody .= "endif;\n";
                }
                break;

            default:
                if (preg_match('!^/(\w+)$!', $name, $m)) {
                    if (end($this->_blockStack) != $m[1]) {
                        $this->doError1('errors.tpl.tag.block.end.missing', end($this->_blockStack));
                    } else {
                        array_pop($this->_blockStack);
                        if (function_exists($fct = 'jtpl_block_'.$this->outputType.'_'.$m[1])) {
                            $res = $fct($this, false, null);
                        } elseif (function_exists($fct = 'jtpl_block_common_'.$m[1])) {
                            $res = $fct($this, false, null);
                        } else {
                            $this->doError1('errors.tpl.tag.block.begin.missing', $m[1]);
                        }
                    }
                } elseif (preg_match('/^meta_(\w+)$/', $name, $m)) {
                    if ($path = $this->_getPlugin('meta', $m[1])) {
                        $this->_parseMeta($args, $path[1]);
                        $this->_pluginPath[$path[0]] = true;
                    } else {
                        $this->doError1('errors.tpl.tag.meta.unknown', $m[1]);
                    }
                    $res = '';
                } elseif ($path = $this->_getPlugin('block', $name)) {
                    require_once $path[0];
                    $argfct = $this->_parseFinal($args, $this->_allowedAssign, array(';'), true);
                    $fct = $path[1];
                    $res = $fct($this, true, $argfct);
                    array_push($this->_blockStack, $name);
                } elseif ($path = $this->_getPlugin('cfunction', $name)) {
                    require_once $path[0];
                    $argfct = $this->_parseFinal($args, $this->_allowedAssign, array(';'), true);
                    $fct = $path[1];
                    $res = $fct($this, $argfct);
                } elseif ($path = $this->_getPlugin('function', $name)) {
                    $argfct = $this->_parseFinal($args, $this->_allowedAssign);
                    $res = $path[1].'( $t'.(trim($argfct) != '' ? ','.$argfct : '').');';
                    $this->_pluginPath[$path[0]] = true;
                } elseif (isset($this->_userFunctions[$name])) {
                    $argfct = $this->_parseFinal($args, $this->_allowedAssign);
                    $res = $this->_userFunctions[$name].'( $t'.(trim($argfct) != '' ? ','.$argfct : '').');';
                } else {
                    $this->doError1('errors.tpl.tag.function.unknown', $name);
                }
        }

        return $res;
    }

    /**
     * analyse a pragma tag : `{! !}`
     *
     * @param string $expr the content of the tag
     */
    protected function _parsePragma($expr)
    {
        if (!preg_match('/^\!\s*([a-z0-9_]+)\s*(?:=\s*([a-z0-9_]+)\s*)?\!$/', $expr, $m)) {
            $this->doError1('errors.tpl.tag.syntax.invalid', '{'.$expr.'}');
        }

        switch($m[1]) {
            case 'autoescape':
                if (isset($m[2])) {
                    $this->_autoescape = in_array(strtolower($m[2]), array('true', 'on', '1'));
                }
                else {
                    $this->_autoescape = true;
                }
                return '';
        }
        $this->doError1('errors.tpl.tag.pragma.unknown', '{'.$expr.'}');
    }

    /**
     * for plugins, it says if the plugin is inside the given block.
     *
     * @param string $blockName the block to search
     * @param bool   $onlyUpper check only the upper block
     *
     * @return bool true if it is inside the block
     */
    public function isInsideBlock($blockName, $onlyUpper = false)
    {
        if ($onlyUpper) {
            return (end($this->_blockStack) == $blockName);
        }
        for ($i = count($this->_blockStack) - 1; $i >= 0; --$i) {
            if ($this->_blockStack[$i] == $blockName) {
                return true;
            }
        }

        return false;
    }

    /**
     * sub-function which analyse an expression.
     *
     * @param  string  $string  the expression
     * @param  array  $allowed  list of allowed php token
     * @param  array  $exceptChar  list of forbidden characters
     * @param  bool  $splitArgIntoArray  true: split the results on coma
     * @param  string  $sep1
     * @param  string  $sep2
     *
     * @return array|string
     */
    protected function _parseFinal($string, $allowed = array(), $exceptChar = array(';'),
                                    $splitArgIntoArray = false, $sep1 = ',', $sep2 = ',')
    {
        $tokens = token_get_all('<?php '.$string.'?>');

        $results = array();
        $result = '';
        $first = true;
        $inLocale = false;
        $locale = '';
        $bracketcount = $sqbracketcount = 0;
        $firstok = array_shift($tokens);

        // there is a bug, sometimes the first token isn't T_OPEN_TAG...
        if ($firstok == '<' && $tokens[0] == '?' && is_array($tokens[1])
            && $tokens[1][0] == T_STRING && $tokens[1][1] == 'php') {
            array_shift($tokens);
            array_shift($tokens);
        }

        $previousTok = null;

        foreach ($tokens as $tok) {
            if (is_array($tok)) {
                list($type, $str) = $tok;
                $first = false;
                if ($type == T_CLOSE_TAG) {
                    $previousTok = $tok;
                    continue;
                }
                if ($inLocale && in_array($type, $this->_inLocaleOk)) {
                    $locale .= $str;
                } elseif ($type == T_VARIABLE && $inLocale) {
                    $locale .= '\'.$t->_vars[\''.substr($str, 1).'\'].\'';
                } elseif ($type == T_VARIABLE) {
                    if (is_array($previousTok) && $previousTok[0] == T_OBJECT_OPERATOR) {
                        $result .= '{$t->_vars[\''.substr($str, 1).'\']}';
                    } else {
                        $result .= '$t->_vars[\''.substr($str, 1).'\']';
                    }
                } elseif ($type == T_WHITESPACE || in_array($type, $allowed)) {
                    if (!$this->trusted && $type == T_STRING && defined($str)
                        && !in_array(strtoupper($str), $this->_allowedConstants)) {
                        $this->doError2('errors.tpl.tag.constant.notallowed', $this->_currentTag, $str);
                    }
                    if ($type == T_WHITESPACE) {
                        $str = preg_replace("/(\s+)/ms", ' ', $str);
                    }
                    $result .= $str;
                } else {
                    $this->doError2('errors.tpl.tag.phpsyntax.invalid', $this->_currentTag, $str);
                }
            } else {
                if ($tok == '@') {
                    if ($inLocale) {
                        $inLocale = false;
                        if ($locale == '') {
                            $this->doError1('errors.tpl.tag.locale.invalid', $this->_currentTag);
                        } else {
                            $result .= $this->getCompiledLocaleRetriever($locale);
                            $locale = '';
                        }
                    } else {
                        $inLocale = true;
                    }
                } elseif ($inLocale && ($tok == '.' || $tok == '~')) {
                    $locale .= $tok;
                } elseif ($inLocale || in_array($tok, $exceptChar)
                          || ($first && $tok != '!' && $tok != '(')) {
                    $this->doError2('errors.tpl.tag.character.invalid', $this->_currentTag, $tok);
                } elseif ($tok == '(') {
                    ++$bracketcount;
                    $result .= $tok;
                } elseif ($tok == ')') {
                    --$bracketcount;
                    $result .= $tok;
                } elseif ($tok == '[') {
                    ++$sqbracketcount;
                    $result .= $tok;
                } elseif ($tok == ']') {
                    --$sqbracketcount;
                    $result .= $tok;
                } elseif ($splitArgIntoArray && ($tok == $sep1 || $tok == $sep2)
                          && $bracketcount == 0 && $sqbracketcount == 0) {
                    $results[] = $result;
                    $result = '';
                } else {
                    $result .= $tok;
                }
                $first = false;
            }
            $previousTok = $tok;
        }

        if ($inLocale) {
            $this->doError1('errors.tpl.tag.locale.end.missing', $this->_currentTag);
        }

        if ($bracketcount != 0 || $sqbracketcount != 0) {
            $this->doError1('errors.tpl.tag.bracket.error', $this->_currentTag);
        }

        $last = end($tokens);
        if (!is_array($last) || $last[0] != T_CLOSE_TAG) {
            $this->doError1('errors.tpl.tag.syntax.invalid', $this->_currentTag);
        }

        if ($splitArgIntoArray) {
            if ($result != '') {
                $results[] = $result;
            }

            return $results;
        } else {
            return $result;
        }
    }

    abstract protected function getCompiledLocaleRetriever($locale);

    protected function _parseMeta($args, $fct = '')
    {
        if (preg_match('/^(\w+)(\s+(.*))?$/', $args, $m)) {
            if (isset($m[3])) {
                $argfct = $this->_parseFinal($m[3], $this->_allowedInExpr);
            } else {
                $argfct = 'null';
            }
            if ($fct != '') {
                $this->_metaBody .= $fct.'( $t,'."'".$m[1]."',".$argfct.");\n";
            } else {
                $this->_metaBody .= "\$t->_meta['".$m[1]."']=".$argfct.";\n";
            }
        } else {
            $this->doError1('errors.tpl.tag.meta.invalid', $this->_currentTag);
        }
    }

    public function addMetaContent($content)
    {
        $this->_metaBody .= $content."\n";
    }

    /**
     * Try to find a plugin.
     *
     * @param string $type type of plugin (function, modifier...)
     * @param string $name the plugin name
     *
     * @return array|bool an array containing the path of the plugin
     *                    and the name of the plugin function, or false if not found
     */
    abstract protected function _getPlugin($type, $name);

    /**
     * @param string $err the error message code
     * @throws \Exception
     */
    abstract public function doError0($err);

    /**
     * @param string $err the error message code
     * @param string $arg a parameter to insert into the translated message
     * @throws \Exception
     */
    abstract public function doError1($err, $arg);

    /**
     * @param string $err the error message code
     * @param string $arg1 a parameter to insert into the translated message
     * @param string $arg2 a parameter to insert into the translated message
     * @throws \Exception
     */
    abstract public function doError2($err, $arg1, $arg2);
}
