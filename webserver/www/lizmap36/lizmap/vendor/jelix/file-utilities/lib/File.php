<?php

/**
 * @author       Laurent Jouanneau
 * @contributor Julien Issler
 * @contributor  Gérald Croes
 *
 * @copyright    2001-2005 CopixTeam, 2005-2022 Laurent Jouanneau, 2010 Julien Issler
 *
 * @link         http://jelix.org
 * @licence      MIT
 */
namespace Jelix\FileUtilities;

class File
{
    public static $defaultChmod = null;

    /**
     * Reads the content of a file.
     *
     * @param string $filename the filename we're gonna read
     *
     * @return string the content of the file. false if cannot read the file
     */
    public static function read($filename)
    {
        return @file_get_contents($filename, false);
    }

    /**
     * Write a file to the disk.
     * It is using a temporary file and then rename the file. We guess the file system will
     * be smarter than us, avoiding a writing / reading while renaming the file.
     *
     * @author     Gérald Croes
     * @contributor Laurent Jouanneau
     *
     * @copyright  2001-2005 CopixTeam
     *
     * @link http://www.copix.org
     */
    public static function write($file, $data, $chmod = null)
    {
        $_dirname = dirname($file);

        //asking to create the directory structure if needed.
        Directory::create($_dirname);

        if (!@is_writable($_dirname)) {
            if (!@is_dir($_dirname)) {
                throw new \UnexpectedValueException('The directory '.$_dirname.' does not exists');
            }
            throw new \RuntimeException('The directory '.$_dirname.' is not writable');
        }

        // write to tmp file, then rename it to avoid
        // file locking race condition
        $_tmp_file = tempnam($_dirname, 'jelix_');

        if (!($fd = @fopen($_tmp_file, 'wb'))) {
            $_tmp_file = $_dirname.'/'.uniqid('jelix_');
            if (!($fd = @fopen($_tmp_file, 'wb'))) {
                throw new \RuntimeException('Cannot create temporary file '.$_tmp_file);
            }
        }
        fwrite($fd, (string)$data);
        fclose($fd);

        // Delete the file if it allready exists (this is needed on Windows,
        // because it cannot overwrite files with rename()
        if (file_exists($file)) {
            unlink($file);
        }
        rename($_tmp_file, $file);
        if ($chmod === null) {
            $chmod = self::$defaultChmod;
        }
        if ($chmod) {
            chmod($file, $chmod);
        }

        return true;
    }

    /**
     * get the MIME Type of a file.
     *
     * @param string $file The full path of the file
     *
     * @return string the MIME type of the file
     *
     * @author Julien Issler
     */
    public static function getMimeType($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($finfo, $file);
        finfo_close($finfo);

        return $type;
    }

    /**
     * get the MIME Type of a file, only with its name.
     *
     * @param string $fileName the file name
     *
     * @return string the MIME type of the file
     */
    public static function getMimeTypeFromFilename($fileName)
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (array_key_exists($ext, self::$customMimeTypes)) {
            return self::$customMimeTypes[$ext];
        } else if (array_key_exists($ext, self::$mimeTypes)) {
            return self::$mimeTypes[$ext];
        }
        return 'application/octet-stream';
    }

    protected static $customMimeTypes = array();

    /**
     * Register some mimetypes so they can be used by File::getMimeTypeFromFilename()
     *
     * @param array $mimeTypes list of mimetypes. Keys are file suffixes, values
     *                         are mimetypes
     */
    public static function registerMimeTypes($mimeTypes) {
        self::$customMimeTypes = array_merge(self::$customMimeTypes, $mimeTypes);
    }

    protected static $mimeTypes = array(

        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'xhtml' => 'application/xhtml+xml',
        'xht' => 'application/xhtml+xml',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'xslt' => 'application/xslt+xml',
        'xsl' => 'application/xml',
        'dtd' => 'application/xml-dtd',
        'atom' => 'application/atom+xml',
        'mathml' => 'application/mathml+xml',
        'rdf' => 'application/rdf+xml',
        'smi' => 'application/smil',
        'smil' => 'application/smil',
        'vxml' => 'application/voicexml+xml',
        'latex' => 'application/x-latex',
        'tcl' => 'application/x-tcl',
        'tex' => 'application/x-tex',
        'texinfo' => 'application/x-texinfo',
        'wrl' => 'model/vrml',
        'wrml' => 'model/vrml',
        'ics' => 'text/calendar',
        'ifb' => 'text/calendar',
        'sgml' => 'text/sgml',
        'htc' => 'text/x-component',

        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'ico' => 'image/x-icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',
        'djvu' => 'image/vnd.djvu',
        'djv' => 'image/vnd.djvu',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',
        'tar' => 'application/x-tar',
        'gz' => 'application/x-gzip',
        'tgz' => 'application/x-gzip',

        // audio/video
        'mp2' => 'audio/mpeg',
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',
        'mpeg' => 'video/mpeg',
        'mpg' => 'video/mpeg',
        'mpe' => 'video/mpeg',
        'wav' => 'audio/wav',
        'aiff' => 'audio/aiff',
        'aif' => 'audio/aiff',
        'avi' => 'video/msvideo',
        'wmv' => 'video/x-ms-wmv',
        'ogg' => 'application/ogg',
        'flv' => 'video/x-flv',
        'dvi' => 'application/x-dvi',
        'au' => 'audio/basic',
        'snd' => 'audio/basic',
        'mid' => 'audio/midi',
        'midi' => 'audio/midi',
        'm3u' => 'audio/x-mpegurl',
        'm4u' => 'video/vnd.mpegurl',
        'ram' => 'audio/x-pn-realaudio',
        'ra' => 'audio/x-pn-realaudio',
        'rm' => 'application/vnd.rn-realmedia',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',
        'swf' => 'application/x-shockwave-flash',

        // ms office
        'doc' => 'application/msword',
        'docx' => 'application/msword',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'xlm' => 'application/vnd.ms-excel',
        'xla' => 'application/vnd.ms-excel',
        'xld' => 'application/vnd.ms-excel',
        'xlt' => 'application/vnd.ms-excel',
        'xlc' => 'application/vnd.ms-excel',
        'xlw' => 'application/vnd.ms-excel',
        'xll' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pps' => 'application/vnd.ms-powerpoint',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );
}
