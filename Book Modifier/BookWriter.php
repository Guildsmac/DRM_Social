<?php
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Auxiliary Classes\\CopyDirectory.php";
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Auxiliary Classes\\FolderCreator.php";
include_once "BookModifier.php";

class BookWriter{
    private $newFolder, $pathList, $completePath;
    public function __construct($folderName){
        if(!file_exists($folderName))
            mkdir($folderName, 0777);
        $folder = new FolderCreator();
        $this->newFolder = $folder->getNewFolder($folderName, "book");

    }

    private function setFiles($bookReader){
        $this->setPathList($bookReader);
        new CopyDirectory($bookReader->getBookPath(), $this->newFolder);

    }

    public function modifyBook($bookReader, $htmlPath, $cssPath){
        $this->setFiles($bookReader);
        $this->insertDRM($htmlPath, $cssPath);

    }

    private function insertDRM($htmlPath, $cssPath){
        $eM = new BookModifier();
        foreach($this->pathList as $i)
            $eM->insert($this->newFolder . "\\" . $i, $htmlPath, $cssPath);


    }

    private function setPathList($bookReader){
        $this->pathList = $bookReader->getPathList();
        $this->completePath = $bookReader->getPathList();
        for($i = 0; $i<sizeof($this->pathList); $i++)
            $this->pathList[$i] = substr($this->pathList[$i], strlen($bookReader->getBookPath())+1, strlen($this->pathList[$i]));


    }
    public function __toString(){
        $r = "Folder Path: " . $this->newFolder .  "<br>" . "---------------------------------------" . "<br>" . "List of Paths(INCOMPLETE):" . "<br>";
        foreach($this->pathList as $i)
            $r = $r . "    " . $i . "<br>";

        $r = $r . "---------------------------------------" . "<br>" . "List of Paths(COMPLETE):" . "<br>";
        foreach($this->completePath as $i)
            $r = $r . "    " . $i . "<br>";

        return $r;
    }

}