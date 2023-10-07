 <?php
/**
 * Plugin from smarty project and adapted for jtpl.
 *
 * @copyright  2001-2003 ispi of Lincoln, Inc.
 *
 * @link http://smarty.php.net/
 * @link http://jelix.org/
 * @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

/**
 * modifier plugin : wrap a string of text at a given length. Same parameters as
 * the php wordwrap function
 * <pre>{$mytext|wordwrap}
 * {$mytext|wordwrap:40}
 * {$mytext|wordwrap:45:"\n"}
 * {$mytext|wordwrap:60:"\n":true}
 * </pre>.
 *
 * @param string $string
 * @param int $length
 * @param string $break
 * @param bool $cut
 *
 * @return string
 */
function jtpl_modifier_common_wordwrap($string, $length = 80, $break = "\n", $cut = false)
{
    return wordwrap($string, $length, $break, $cut);
}
