<?php
/**
 * @author     Laurent Jouanneau
 * @contributor Gerald Croes, Julien Issler, Yannick Le Guédart, Dominique Papin
 * @copyright 2005-2022 Laurent Jouanneau, 2001-2005 CopixTeam, 2008 Julien Issler, 2008 Yannick Le Guédart, 2008 Dominique Papin
 *
 * @link       https://jelix.org
 * @licence    MIT
 */
namespace Jelix\PropertiesFile;

/**
 * reads a properties file
 */
class Parser
{
    /**
     */
    public function __construct()
    {
    }


    /**
     * @param string $fileName  the file to parse
     * @param Properties $properties the container when readed properties will be stored
     * @param string $charset  the charset used into the file
     * @throws \Exception
     * @throws SyntaxException
     */
    public function parseFromFile($fileName, Properties $properties, $charset = 'UTF-8')
    {
        $f = @fopen($fileName, 'r');

        if ($f === false) {
            throw new \Exception('Cannot load the properties file ' . $fileName);
        }
        $nexLine = function () use ($f) {
            if (feof($f)) {
                fclose($f);
                return false;
            }
            return fgets($f);
        };

        try {
            $this->readContent($properties, $nexLine, $charset);
        } catch (SyntaxException $e) {
            fclose($f);
            $msg = sprintf($e->getMessage(), 'file '.$fileName);
            throw new SyntaxException($msg, $e->getCode(), $e);
        }
    }

    /**
     * @param Properties $properties
     * @param callable $nextLine
     * @param string $charset
     */
    protected function readContent(Properties $properties, callable $nextLine, $charset)
    {
        $utf8Mod = ($charset == 'UTF-8') ? 'u' : '';
        $unbreakablespace = ($charset == 'UTF-8') ? chr(0xc2).chr(160) : chr(160);
        $escapedChars = array('\#', '\n', '\w', '\S', '\s');
        $unescape = array('#', "\n", ' ', $unbreakablespace, ' ');
        $multiline = false;
        $lineNumber = 0;
        $key = '';

        while (($line = $nextLine()) !== false) {
            ++$lineNumber;
            $line = rtrim($line);

            if ($multiline) {
                // the current line is the part of the value of the previous readed property
                if (preg_match("/^\s*(.*)\s*(\\\\?)$/U".$utf8Mod, $line, $match)) {
                    $multiline = ($match[2] == '\\');
                    if (strlen($match[1])) {
                        $sp = preg_split('/(?<!\\\\)\#/', $match[1], -1, PREG_SPLIT_NO_EMPTY);
                        $properties[$key] .= ' '.str_replace($escapedChars, $unescape, trim($sp[0]));
                    } else {
                        $properties[$key] .= ' ';
                    }
                } else {
                    throw new SyntaxException('Syntax error in properties %s, line '.$lineNumber);
                }
            } elseif (preg_match("/^\s*(.+)\s*=\s*(.*)\s*(\\\\?)$/U".$utf8Mod, $line, $match)) {
                // we got a key=value
                $key = $match[1];
                $multiline = ($match[3] == '\\');
                $sp = preg_split('/(?<!\\\\)\#/', $match[2], -1, PREG_SPLIT_NO_EMPTY);
                if (count($sp)) {
                    $value = trim($sp[0]);
                } else {
                    $value = '';
                }
                $properties[$key] = str_replace($escapedChars, $unescape, $value);
            } elseif (preg_match("/^\s*(\#.*)?$/", $line, $match)) {
                // ok, just a comment, let's ignore it
            } else {
                throw new SyntaxException('Syntax error in properties content line '.$lineNumber);
            }
        }
    }
}
