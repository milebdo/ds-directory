<?php
/**
 * index.php
 * @author Ismail <is.tmdg86@gmail.com>
 * @since 2017.12.23
 */

include_once('ScanDirectoryAndFile.php');
include_once('FilterAndCountText.php');

showData();

function showData()
{
    processData();
    $showFile = new FilterAndCountText();
    $showFile->show();
}

function processData()
{
    $scannedFile = new ScanDirectoryAndFile();
    $scannedFile->pathDir = 'file-dir';
    $result = $scannedFile->scanDirectory();
    if (empty($result)) {
        echo 'Can`t find any file';
    }

    foreach ($result as $key => $file) {
        $filterFile = new FilterAndCountText();
        $filterFile->pathFile = $file;
        $filterFile->mapingData();
    }
}
