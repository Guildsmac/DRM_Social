<?php
class BookModifier{

    public function insert($path, $htmlFooterPath, $cssFooterPath){
        if(pathinfo($path, PATHINFO_EXTENSION)=='html' ||
           pathinfo($path, PATHINFO_EXTENSION)=='xhtml') {
            copy($this->insertOnHTML($path, $htmlFooterPath), $path);
        }
        else if(pathinfo($path, PATHINFO_EXTENSION)=='css')
            copy($this->insertOnCSS($path, $cssFooterPath), $path);
    }

    private function insertOnHTML($path, $footerPath){
        $file = fopen($path, 'r');
        $footerCode = $this->getHTMLDRM($footerPath, "</footer>");
        if(!file_exists("temp"))
            mkdir("temp", 0777);
        $newPath = "temp\\" . basename($path);
        $newFile = fopen ($newPath, 'w');
        while(!feof($file)){
            $buffer = fgets($file);
            if(strrpos($buffer, "</body>")!==false)
                $buffer = $this->insertDRMOnLine($buffer, $footerCode);

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

    private function insertDRMOnLine($buffer, $footerCode){
        $buffer = trim($buffer, ' ');
        $closingBodyPosition = strrpos($buffer, "</body>");
        $firstPosEnd = $closingBodyPosition-1;
        $secondPosStart = $closingBodyPosition + strlen("</body>");
        $secondPosEnd = strlen($buffer);
        if($closingBodyPosition == 0 && strlen($buffer)==strlen("</body>")+1)
            $buffer = trim($footerCode) . $buffer;

        else if($closingBodyPosition == 0)
            $buffer = trim($footerCode) . substr($buffer, $secondPosStart, strlen($buffer)) ."</body>";

        else if($closingBodyPosition+strlen("</body>")==strlen($buffer)-1)
            $buffer = substr($buffer, 0, $firstPosEnd+1) . trim($footerCode) . "</body>";

        else{
            $buffer = substr($buffer, 0, $firstPosEnd+1) . trim($footerCode) .
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