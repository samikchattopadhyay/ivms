<?php
namespace App\Helpers;

use Smalot\PdfParser\Parser;

class IvmsTextExtractor
{

    /**
     * Extract text from Word Document (docx)
     *
     * @param string $archiveFile
     * @param string $dataFile
     * @return string
     */
    public static function docx2text($filename, $dataFile = "word/document.xml")
    {
        $zip = new \ZipArchive();
        if (true === $zip->open($filename)) {
            $index = $zip->locateName($dataFile);
            $data = $zip->getFromIndex($index);
            $zip->close();
            $xml = new \DOMDocument();
            $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            return strip_tags($xml->saveXML());
        }
        $zip->close();
        return "";
    }

    /**
     * Extract text from Word Document (doc)
     *
     * @param string $filename
     * @return mixed
     */
    public static function doc2text($filename)
    {
        $outtext = "";
        $fileHandle = fopen($filename, "r");
        $line = @fread($fileHandle, filesize($filename));
        $lines = explode(chr(0x0D), $line);
        foreach ($lines as $thisline) {
            $pos = strpos($thisline, chr(0x00));
            if (($pos !== FALSE) || (strlen($thisline) == 0)) {} else {
                $outtext .= $thisline . " ";
            }
        }
        $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/", "", $outtext);
        return $outtext;
    }

    /**
     * Extract text from PDF Document
     *
     * @param string $filename
     * @return string
     */
    public static function pdf2txt($filename)
    {
        $pageContent = '';
        $parser = new Parser();
        $output = $parser->parseFile($filename);
        if ($output) {
            $pages = $output->getPages();
            foreach ($pages as $page) {
                $pageContent .= $page->getText();
            }
        }
        return $pageContent;
    }
    
    /**
     * Extract text from doc, docx and pdf files
     * 
     * @param string $filename
     */
    public static function extract($filename)
    {
        $file = pathinfo($filename);
        
        if ($file['extension'] == 'doc') {
            return self::doc2text($filename);
        } elseif ($file['extension'] == 'docx') {
            return self::docx2text($filename);
        } elseif ($file['extension'] == 'pdf') {
            return self::pdf2txt($filename);
        }
        
        return '';
    }
}
