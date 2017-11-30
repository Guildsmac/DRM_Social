<?php
include_once "Book Modifier\\BookReader.php"; //CAMINHO PARA A CLASSE BOOKREADER
include_once "Book Modifier\\BookWriter.php"; //CAMINHO PARA A CLASSE BOOKWRITER
include_once "Auxiliary Classes\\Zipper.php"; //CAMINHO PARA A CLASSE ZIPPER
include_once "Auxiliary Classes\\FolderCleaner.php"; //CAMINHO PARA A CLASSE FOLDER CLEANER

class DRMApplicator{

    public function insert($bookPath, $htmlFooter, $cssFooter, $destinyPath){

        $zipper = new Zipper($bookPath);
        $bookReader = new BookReader();
        $bookReader->readBook($zipper->getExtractedFolder());
        $bookWriter = new BookWriter($destinyPath);
        $bookWriter->modifyBook($bookReader, $htmlFooter, $cssFooter);
        $zipper->zipFile($bookWriter->getNewFolderPath());
        new FolderCleaner("temp");
        new FolderCleaner("tempBook");


    }

}