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
    $id = processData();
    $showFile = new FilterAndCountText();
    if (!empty($id)) {
        $showFile->flag = FilterAndCountText::FIRST_COUNT;
        $showFile->idFile = $id;
    }
    $showFile->show();
}

function processData()
{
    $idFiles = [];
    $scannedFile = new ScanDirectoryAndFile();
    $scannedFile->pathDir = 'file-dir';
    $result = $scannedFile->scanDirectory();
    if (empty($result)) {
        echo 'Can`t find any file';
    }

    foreach ($result as $key => $file) {
        $filterFile = new FilterAndCountText();
        $filterFile->pathFile = $file;
        $filterFile->totalFiles = count($result);
        $id = $filterFile->mapingData();
        if ($id !== null) {
            array_push($idFiles, $id);
        }
    }
    return $idFiles;
}
