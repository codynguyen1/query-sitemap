<?php
require './config.php';
$urlsFound = array();

$startedOfAll = microtime(true);
//get url for each xml file
foreach ($aXmlLinks as $i =>$sTmpUrl){
    unset($aXmlLinks[$i]);
    $aTmp = getLinkFromSitemapUrl($sTmpUrl, $stringsToFilter);        
    $urlsFound = array_merge($urlsFound, $aTmp);
}

//find duplicate
//print_r("Array of URLs:\n");
$urlRenderered = 0;
$urlsFound = array_count_values($urlsFound);
$totalPages = count($urlsFound);
foreach ($urlsFound as $url => $count) {
    $urlRenderered++;
    //delay for one second or rendertron will timeout
    sleep(1);
    echo("Rendering: $url - $urlRenderered of ". $totalPages. " URL(s)\n ");
    $startTime = microtime(true);
    $result = rendertronTheUrl($url, $renderTronUrl, $renderMobile);
    $runningTime = microtime(true) - $startTime;
    echo("result: $result  - took: $runningTime second(s)\n");
    echo("------- \n");
}
$wholeProcessTook = microtime(true) - $startedOfAll;
echo "Number of URL(s) rendered: $urlRenderered \n";
echo "Whole process took: $wholeProcessTook second(s) \n";
echo "-----------------------------------------\n";
exit();


function getLinkFromSitemapUrl($sUrl, $stringsToFilter) {
    echo "Get link from: $sUrl \n";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0");
    curl_setopt($ch, CURLOPT_URL, $sUrl);
    $data = curl_exec($ch);
    $error= curl_error($ch);
    curl_close($ch);
    $links = array();
    $count = preg_match_all('@<loc>(.+?)<\/loc>@', $data, $matches);
    for ($i = 0; $i < $count; ++$i) {
        $urlFound = $matches[0][$i];
        $urlFound = str_replace('<loc>', '', $urlFound);
        $urlFound = str_replace('</loc>', '', $urlFound);
        foreach($stringsToFilter as $str2Ft) {
            $urlFound = str_replace($str2Ft, '/', $urlFound);
        }
        $links[] = $urlFound;
    }
    return $links;  
}

function rendertronTheUrl($sUrl, $renderTronUrl, $renderMobile) {
    $rendertronLink = $renderTronUrl . $sUrl ;
    if ($renderMobile) {
        $rendertronLink .= '?mobile=true';
    }

    $ch = curl_init();
    $optArray = [
        CURLOPT_URL => $rendertronLink,
        CURLOPT_RETURNTRANSFER => true
    ];
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return $httpcode;
}
?>

