<?php

class CopyDirectory{
    public function __construct($source,$destiny) {
        $dir = opendir($source);
        @mkdir($destiny);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($source . '/' . $file) )
                    new CopyDirectory($source . '/' . $file,$destiny . '/' . $file);

                else
                    copy($source . '/' . $file,$destiny . '/' . $file);

            }
        }
        closedir($dir);
    }

}