<?php

class BookReader{
    private $pathList = array();
    private $needCSS = array();
    private $bookPath;

    private function isPathValid($path){
        if(file_exists($path))
            return true;
        return false;

    }

    private function setPath($path){
        if(!$this->isPathValid($path))
            return false;
        $this->bookPath = $path;
        return true;

    }

    private function setPathList(){
        $this->dealDirectory($this->bookPath);
    }

    public function readBook($path){
        $this->setPath($path);
        $this->setPathList();
    }

    private function dealDirectory($path){
        $iterator = new DirectoryIterator($path);
        foreach($iterator as $i){
            if(!$i->isDot()){
                if($i->isDir())
                    $this->dealDirectory($i->getPathName());
                else {
                    $this->pathList[] = $i->getPathName();
                    if(!$this->hasOcurrence($this->needCSS, substr($i->getPathName(), 0, strrpos($i->getPathName(), '\\'))))
                        $this->needCSS[] = substr($i->getPathName(), 0, strrpos($i->getPathName(), '\\'));
                }
            }
        }
    }

    private function hasOcurrence($array, $comp){
        foreach($array as $i){
            if($i==$comp)
                return true;

        }
        return false;
    }

    public function addPathList($path){
        $this->pathList[] = $path;
    }

    public function getNeedCSS(){
        return $this->needCSS;
    }

    public function getPathList(){
        return $this->pathList;

    }

    public function getBookPath(){
        return $this->bookPath;

    }

    public function __toString(){
        $r = "List of Paths: " . '<br>';

        foreach($this->pathList as $i)
            $r = $r . $i . "<br>";

        $r = $r . "Path name: " . "<br>" . $this->bookPath . "<br>";
        return $r;

    }

}