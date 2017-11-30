<?php

//CLASSE PRINCIPAL PARA SER INSTANCIADA E ENTÃO USADA PARA INSERIR A DRM NOS LIVROS

include_once "Book Modifier\\BookReader.php"; //CAMINHO PARA A CLASSE BOOKREADER
include_once "Book Modifier\\BookWriter.php"; //CAMINHO PARA A CLASSE BOOKWRITER
include_once "Auxiliary Classes\\Zipper.php"; //CAMINHO PARA A CLASSE ZIPPER
include_once "Auxiliary Classes\\FolderCleaner.php"; //CAMINHO PARA A CLASSE FOLDER CLEANER

class DRMApplicator{

    public function insert($bookPath     //Diretório onde o arquivo .epub está localizado
                           , $htmlFooter //Diretório onde o arquivo do rodapé em HTML está localizado
                           , $cssFooter  //Diretório onde o arquivo do rodapé em CSS está localizado
                           , $destinyPath//Diretório onde os livros finais devem ser armazenados(Só uma pasta principal)
                          ){

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
