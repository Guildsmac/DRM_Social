<?php

class FolderCleaner{

    public function __construct($pathToErase){
        $dir = $pathToErase;
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        foreach ( $ri as $file ) {
            if($file->isDir()){
                if(empty($file))
                    rmdir($file);
            }else
                unlink($file);
            if(file_exists($file)) {
                if(empty($file))
                    rmdir($file);
            }
        }

    }

}