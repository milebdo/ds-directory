<?php
/**
 * Scan Directories
 * @author Ismail <is.tmdg86@gmail.com>
 * @since 2017.12.23
 */

class ScanDirectoryAndFile
{
    public $pathDir;

    public function scanDirectory()
    {
        $files = [];
        $directories = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->pathDir)
        );
        foreach ($directories as $file) {
            if (!$file->isDir() && preg_match("/^[^\.].*$/", $file->getFilename())){
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }
}
