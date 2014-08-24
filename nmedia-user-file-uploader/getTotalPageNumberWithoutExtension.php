<?php

function getTotalPagesDocx($file){
    if(!file_exists($file))return null;
    if (!$fp = @fopen($file,"r"))return null;
    $max=0;
    while(!feof($fp)) {
        $line = fgets($fp,255);
        $pos1 = strpos($line, '<Pages>');
        $pos2 = strpos($line, '</Pages>');

        if (($pos1 !== false) && ($pos2 !== false)) {
            $a = substr($line, $pos1+7);
            $max = substr($a,0,$pos2-$pos1+7);
        }
    }
    fclose($fp);
    return (int)$max;
}

function getTotalPagesOdt($file){
    if(!file_exists($file))return null;
    if (!$fp = @fopen($file,"r"))return null;
    $max=0;
    while(!feof($fp)) {
        $line = fgets($fp,255);
        $pos1 = strpos($line, 'meta:page-count=');
        $pos2 = strpos($line, 'meta:paragraph-count=');
        $pos2 = $pos2 - 2;//bosluk var tirnak isaretini cikardik

        if (($pos1 !== false) && ($pos2 !== false)) {
            $a = substr($line, $pos1+17);
            $max = substr($a,0,$pos2-$pos1+17);
        }
    }
    fclose($fp);
    return (int)$max;
}

function getNumPagesInPDF($file)
{
    //http://www.hotscripts.com/forums/php/23533-how-now-get-number-pages-one-document-pdf.html
    if(!file_exists($file))return null;
    if (!$fp = @fopen($file,"r"))return null;
    $max=0;
    while(!feof($fp)) {
            $line = fgets($fp,255);
            if (preg_match('/\/Count [0-9]+/', $line, $matches)){
                    preg_match('/[0-9]+/',$matches[0], $matches2);
                    if ($max<$matches2[0]) $max=$matches2[0];
            }
    }
    fclose($fp);

    return (int)$max;
}

/*
//Example
$filename= iconv('utf-8', 'cp1252', 'Aide_Syntaxe — Wikipédia.pdf');
echo getNumPagesInPDF($filename);
*/


//kaynak: https://stackoverflow.com/questions/1143841/count-the-number-of-pages-in-a-pdf-in-only-php
?>