<?php

/**
 * @author       Laurent Jouanneau
 * @contributor  Christophe Thiriot
 * @contributor  Loic Mathaud
 * @contributor  Bastien Jaillot
 * @contributor Julien Issler
 *
 * @copyright    2005-2018 Laurent Jouanneau, 2006 Christophe Thiriot, 2006 Loic Mathaud, 2008 Bastien Jaillot, 2009 Julien Issler
 *
 * @link         http://jelix.org
 * @licence      MIT
 */
namespace Jelix\FileUtilities;

class Directory
{
    public static $defaultChmod = 0755;

    /**
     * create a directory.
     *
     * @return bool false if the directory did already exist
     */
    public static function create($dir, $chmod = null)
    {
        if (!file_exists($dir)) {
            if ($chmod === null) {
                $chmod = self::$defaultChmod;
            }
            mkdir($dir, $chmod, true);
            // php mkdir apply umask on the given mode, so we must to
            // do a chmod manually.
            chmod($dir, $chmod);
            return true;
        }

        return false;
    }

    /**
     * Recursive function deleting a directory.
     *
     * @param string $path      The path of the directory to remove recursively
     * @param bool   $deleteDir If the path must be deleted too
     *
     * @author Loic Mathaud
     */
    public static function remove($path, $deleteDir = true)
    {
        // minimum security check
        if ($path == '' || $path == '/' || $path == DIRECTORY_SEPARATOR) {
            throw new \InvalidArgumentException('The root cannot be removed !!');
        }

        if (!file_exists($path)) {
            return true;
        }

        $dir = new \DirectoryIterator($path);
        foreach ($dir as $dirContent) {
            // file deletion
            if ($dirContent->isFile() || $dirContent->isLink()) {
                unlink($dirContent->getPathName());
            } else {
                // recursive directory deletion
                if (!$dirContent->isDot() && $dirContent->isDir()) {
                    self::remove($dirContent->getPathName());
                }
            }
        }
        unset($dir);
        unset($dirContent);

        // removes the parent directory
        if ($deleteDir) {
            rmdir($path);
        }
    }

    /**
     * Recursive function deleting all files into a directory except those indicated.
     *
     * @param string $path      The path of the directory to remove recursively
     * @param array  $except    filenames and suffix of filename, for files to NOT delete
     * @param bool   $deleteDir If the path must be deleted too
     *
     * @return bool true if all the content has been removed
     *
     * @author Loic Mathaud
     */
    public static function removeExcept($path, $except, $deleteDir = true)
    {
        if (!is_array($except) || !count($except)) {
            throw new \InvalidArgumentException('list of exception is not an array or is empty');
        }

        if ($path == '' || $path == '/' || $path == DIRECTORY_SEPARATOR) {
            throw new \InvalidArgumentException('The root cannot be removed !!');
        }

        if (!file_exists($path)) {
            return true;
        }

        $allIsDeleted = true;
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $dirContent) {
            // test if the basename matches one of patterns
            $exception = false;
            foreach ($except as $pattern) {
                if ($pattern[0] == '*') { // for pattern like *.foo
                    if ($dirContent->getBasename() != $dirContent->getBasename(substr($pattern, 1))) {
                        $allIsDeleted = false;
                        $exception = true;
                        break;
                    }
                } elseif ($pattern == $dirContent->getBasename()) {
                    $allIsDeleted = false;
                    $exception = true;
                    break;
                }
            }
            if ($exception) {
                continue;
            }
            // file deletion
            if ($dirContent->isFile() || $dirContent->isLink()) {
                unlink($dirContent->getPathName());
            } else {
                // recursive directory deletion
                if (!$dirContent->isDot() && $dirContent->isDir()) {
                    $removed = self::removeExcept($dirContent->getPathName(), $except, true);
                    if (!$removed) {
                        $allIsDeleted = false;
                    }
                }
            }
        }
        unset($dir);
        unset($dirContent);

        // removes the parent directory
        if ($deleteDir && $allIsDeleted) {
            rmdir($path);
        }

        return $allIsDeleted;
    }

    /**
     * Copy a content directory into an other
     * @param string $srcDir  the directory from which content will be copied
     * @param string $destDir the directory in which content will be copied
     * @param boolean $overwrite set to false to not overwrite existing files in
     * the target directory
     */
    static function copy($srcDir, $destDir, $overwrite = true) {
        Directory::create($destDir);

        $dir = new \DirectoryIterator($srcDir);
        foreach ($dir as $dirContent) {
            if ($dirContent->isFile() || $dirContent->isLink()) {
                $target = $destDir.'/'.$dirContent->getFilename();
                if ($overwrite || !file_exists($target)) {
                    copy($dirContent->getPathName(), $target);
                }
            } else if (!$dirContent->isDot() && $dirContent->isDir()) {
                self::copy($dirContent->getPathName(), $destDir.'/'.$dirContent->getFilename(), $overwrite);
            }
        }
    } 
}
