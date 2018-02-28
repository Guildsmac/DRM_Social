<?php
class BookModifier{
    //TEST
    public function insert($path, $htmlFooterPath){
        if(pathinfo($path, PATHINFO_EXTENSION)=='html' ||
           pathinfo($path, PATHINFO_EXTENSION)=='xhtml') {
            copy($this->insertOnHTML($path, $htmlFooterPath,
                        "</footer>", "</html>"), $path);
            copy($this->insertOnHTML($path, $htmlFooterPath,
                "</link>", "</head>"), $path);
        }
    }

    private function insertOnHTML($path, $footerPath, $endingFooterTag, $endingBookTag){
        $file = fopen($path, 'r');
        $footerCode = $this->getHTMLDRM($footerPath, $endingFooterTag);
        if(!file_exists("temp"))
            mkdir("temp", 0777);
        $newPath = "temp\\" . basename($path);
        $newFile = fopen ($newPath, 'w');
        while(!feof($file)){
            $buffer = fgets($file);
            if(strrpos($buffer, $endingBookTag)!==false)
                $buffer = $this->insertDRMOnLine($buffer, $footerCode, $endingBookTag);

            fwrite($newFile, $buffer);

        }
        fclose($file);
        fclose($newFile);
        return $newPath;
    }

    private function getHTMLDRM($footerPath, $endingTag){
        $footerFile = fopen($footerPath, 'r');
        while(!feof($footerFile)){
            $buffer = fgets($footerFile);

            if(strrpos($buffer, $endingTag)!==false)
                return $buffer;
        }
    }

    private function insertDRMOnLine($buffer, $footerCode, $endingFooterTag){
        $buffer = trim($buffer, ' ');
        $closingBodyPosition = strrpos($buffer, $endingFooterTag);
        $firstPosEnd = $closingBodyPosition-1;
        $secondPosStart = $closingBodyPosition + strlen($endingFooterTag);
        $secondPosEnd = strlen($buffer);
        if($closingBodyPosition == 0 && strlen($buffer)==strlen($endingFooterTag)+1)
            $buffer = trim($footerCode) . $buffer;

        else if($closingBodyPosition == 0)
            $buffer = trim($footerCode) . substr($buffer, $secondPosStart, strlen($buffer)) . $endingFooterTag;

        else if($closingBodyPosition+strlen($endingFooterTag)==strlen($buffer)-1)
            $buffer = substr($buffer, 0, $firstPosEnd+1) . trim($footerCode) . $endingFooterTag;

        else{
            $buffer = substr($buffer, 0, $firstPosEnd+1) . trim($footerCode) . $endingFooterTag .
                substr($buffer, $secondPosStart, $secondPosEnd);
        }
        return $buffer;
    }

    private function insertOnCSS($path, $footerPath){
        $file = fopen($path, 'r');
        $footerCode = $this->getCSSDRM($footerPath);
        if(!file_exists("temp"))
            mkdir("temp", 0777);
        $newPath = "temp\\" . basename($path);
        $newFile = fopen($newPath, 'w');
        while(!feof($file)){
            $buffer = fgets($file);
            fwrite($newFile, $buffer);
        }
        fwrite($newFile, $footerCode);
        fclose($file);
        fclose($newFile);
        return $newPath;

    }

    private function getCSSDRM($footerPath){
        $footerFile = fopen($footerPath, 'r');
        $buffer = '';
        while(!feof($footerFile))
            $buffer = $buffer . fgets($footerFile);

        return $buffer;
    }

}