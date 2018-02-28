<?php
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Auxiliary Classes\\CopyDirectory.php"; //CAMINHO PARA A CLASSE COPYDIRECTORY
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Auxiliary Classes\\FolderCreator.php"; // CAMINHO PARA A CLASSE FOLDER CREATOR
include_once "C:\\Users\\Guildsmac\\PhpstormProjects\\DRM Social\\Book Modifier\\BookModifier.php"; //CAMINHO PARA A CLASSE BOOKMODIFIER

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
        $this->insertCSS($bookReader->getNeedCSS(), $cssPath, $bookReader);
        $this->setFiles($bookReader);
        $this->insertDRM($htmlPath);

    }

    private function insertCSS($needList, $cssPath, $bookReader){
        foreach($needList as $i){
            $dir = new DirectoryIterator($i);
            foreach($dir as $file){
                if($file->isFile()){
                    if($file->getExtension()=='xhtml' || $file->getExtension()=='html') {
                        $this->createCSS($dir->getPath() . "\\DRM.css", $cssPath);
                        $bookReader->addPathList($dir->getPath() . "\\DRM.css");
                        break;
                    }
                }
            }
            //ITERAR POR TODOS OS VALORES DA LISTA E INSERIR O ARQUIVO CSS NAS PASTAS QUE POSSUEM HTML
        }
    }

    private function createCSS($destPath, $cssPath){
        $drmCSS = fopen($destPath, "w");
        fclose($drmCSS);
        copy($cssPath, $destPath);
    }

    public function getNewFolderPath(){
        return $this->newFolder;
    }

    private function insertDRM($htmlPath){
        $eM = new BookModifier();
        foreach($this->pathList as $i)
            $eM->insert($this->newFolder . "\\" . $i, $htmlPath);

    }

    private function setPathList($bookReader){
        $this->pathList = $bookReader->getPathList();
        $this->completePath = $bookReader->getPathList();
        for($i = 0; $i<sizeof($this->pathList); $i++){
            $this->pathList[$i] = substr($this->pathList[$i], strlen($bookReader->getBookPath()) + 1, strlen($this->pathList[$i]));
        }

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