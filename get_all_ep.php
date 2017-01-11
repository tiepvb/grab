<?php
function get_by_curl($url,$post='',$vars=''){
    $ch = curl_init(); 
    curl_setopt ($ch, CURLOPT_URL, $url); 
    curl_setopt ($ch, CURLOPT_USERAGENT, "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");  
	if($post){
	curl_setopt ($ch , CURLOPT_POST , 1) ; 
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $vars); 
	}
    curl_setopt ($ch, CURLOPT_HEADER, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
    //curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
    $result = curl_exec ($ch);
    curl_close($ch);
 return $result;
}
//http://tv.zing.vn/series/Tuoi-Thanh-Xuan and page
$url=$_GET['url'];
$code=file_get_contents('compress.zlib://'.$url);
//var_dump($code);die;
preg_match_all('#class="thumb" itemprop="url" href="(.*?)"#',$code,$match);
for($i=count($match[0])-1;$i>=0;$i--){
	$t_url='http://tv.zing.vn'.$match[1][$i].',';
	echo $t_url;
}
