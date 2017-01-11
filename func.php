<?php
function curl($url) { $ch = @curl_init(); curl_setopt($ch, CURLOPT_URL, $url); $head[] = "Connection: keep-alive"; $head[] = "Keep-Alive: 300"; $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; $head[] = "Accept-Language: en-us,en;q=0.5"; curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36'); curl_setopt($ch, CURLOPT_HTTPHEADER, $head); curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); curl_setopt($ch, CURLOPT_TIMEOUT, 60); curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); $page = curl_exec($ch); curl_close($ch); return $page; }
function grab_link($url,$direct=false){
		global $mysql,$tb_prefix,$tpl;
		if(preg_match('/www.youtube.com/i',$url)){
			//$url=gkEncode($url);
			$id_sr=explode('?v=',$url);
			$url=$id_sr[1];
		}
		elseif(preg_match('/fptplay.net/', $url)) {
			$ep=explode('tap-',$url);
			$id=explode('/',$url);
			$id=explode('#',$id[4]);
			$id[0]=str_replace('.html','',$id[0]);
			$fpt=substr($id[0],-24);
			if(!isset($ep[1])) $fptep=1;
			else $fptep=$ep[1];
			$url=file_get_contents('http://xemphimhan.com/jwplayer/fptplay.php?id='.$fpt.'&episode='.$fptep);
			//$url='http://xemphimhan.com/jwplayer/fptplay.php?id='.$fpt.'&episode='.$fptep;
		}
		elseif(preg_match('/picasaweb.google.com/i', $url) || preg_match('/picasaweb2.google.com/i', $url)) {
			$url=str_replace('?noredirect=1','',$url);
			$url=str_replace('picasaweb2.google.com','picasaweb.google.com',$url);
			$ch=explode('lh/photo',$url);
			$i=explode('/',$url);
			$au=explode('authkey=',$url);
			$ipt=explode('#',$url);
			if(isset($au[1])){
				$aut='&authkey='.$au[1];
				$aut=str_replace($ipt[1],'',$aut);
				$aut=str_replace('#','',$aut);
			}else{$aut='';}
			$origin_url='https://picasaweb.google.com/data/feed/tiny/user/'.$i[3].'/photoid/'.$ipt[1].'?alt=jsonm'.$aut;
			$obj_array=getJson($origin_url);
			if(isset($obj_array[2]->url)){
				$url='{file: "'.$obj_array[1]->url.'",label: "360P",type: \'video/mp4\'},{file: "'.$obj_array[2]->url.'",label: "720P", type: \'video/mp4\'}';
			}else{
				$url='{file: "'.$obj_array[1]->url.'",label: "360P",type: \'video/mp4\'}';
			}
		}
		elseif(preg_match('/tv.zing.vn/i', $url)) {
			$i=explode('/',$url);
			if($i[3] == 'episode'){
				$url=curl('http://xemphimhan.com/jwplayer/mzingtv.php?id='.$url.'&episode=2');
				/*$data=file_get_contents('compress.zlib://'.$url);
				$xml=explode('xmlURL: "',$data);
				$xml=explode('"',$xml[1]);
				$code=file_get_contents('compress.zlib://'.$xml[0]);
				preg_match('#<source>(.*?)</source>#', $code, $arrXml);
				$arrXml[1]=str_replace('<![CDATA[','',$arrXml[1]);
				$arrXml[1]=str_replace(']]>','',$arrXml[1]);
				$url=$arrXml[1];*/
			}else{
				$i[5]=str_replace('.html','',$i[5]);
				/*$origin_url='http://api.tv.zing.vn/2.0/media/info?api_key=d04210a70026ad9323076716781c223f&media_id='.$i[5];
				$obj_array=getJsontvzing($origin_url);
				$url='http://'.$obj_array->file_url;
				//$url = file_get_contents('http://xemphimhan.com/jwplayer/zingtv.php?url='.$url);*/
				$url = 'http://xemphimhan.com/jwplayer/mtvzing-'.$i[5].'/1.html5';
			}
		}
		elseif(preg_match('/drive.google.com/i', $url)) {
			$data=curl($url);
			preg_match('#url_encoded_fmt_stream_map","(.*?)"#', $data, $arrXml);
			$arrXml[1]=str_replace('\u003d','=',$arrXml[1]);
			$arrXml[1]=str_replace('\u0026','&',$arrXml[1]);
			$ul=urldecode($arrXml[1]);
			$itag22=explode('itag=22&url=',$ul);
			$itag22=explode('&type',$itag22[1]);
			$itag18=explode('itag=18&url=',$ul);
			$itag18=explode('&type',$itag18[1]);
			if(!isset($itag22[0])) $url='{file: "'.$itag18[0].'",label: "360P",type: \'video/mp4\'}';
			else $url='{file: "'.$itag18[0].'",label: "360P",type: \'video/mp4\'},{file: "'.$itag22[0].'",label: "720P", type: \'video/mp4\'}';
		}
		elseif(preg_match('/photos.google.com/', $url)) {
				$result=curl($url);
				preg_match('#","url(.*?)"#', $result, $arrXml);
				$arrXml[1]=str_replace('\u003d','=',$arrXml[1]);
				$arrXml[1]=str_replace('\u0026','&',$arrXml[1]);
				$ul='url'.urldecode($arrXml[1]);
				$itag37=explode('m37',$ul);
				$itag221=explode('m22',$ul);
				if(isset($itag37[1])){
					$itag371=explode('url=',$ul);
					$itag371=explode('&itag=37',$itag371[1]);
					$itag22=explode('hd1080,url=',$ul);
					$itag22=explode('&itag=22',$itag22[1]);
					$itag18=explode('hd720,url=',$ul);
					$itag18=explode('&itag=18',$itag18[1]);
					$url='{file: "'.$itag18[0].'",label: "360P", type: "video/mp4"},{file: "'.$itag22[0].'",label: "720P", type: "video/mp4"},{file: "'.$itag371[0].'",label: "1080P", type: "video/mp4"}';
				}
				elseif(!isset($itag37[1]) && isset($itag221[1])){
					$itag22=explode('url=',$ul);
					$itag22=explode('&itag=22',$itag22[1]);
					$itag18=explode('hd720,url=',$ul);
					$itag18=explode('&itag=18',$itag18[1]);
					$url='{file: "'.$itag18[0].'",label: "360P", type: "video/mp4"},{file: "'.$itag22[0].'",label: "720P", type: "video/mp4"}';
				}
				elseif(!isset($itag221[1])){
					$itag18=explode('url=',$ul);
					$itag18=explode('&itag=18',$itag18[1]);
					$url='{file: "'.$itag18[0].'",label: "360P", type: "video/mp4"}';
				}
		}
		elseif(preg_match('/goo.gl/', $url)) {
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_NOBODY, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_exec($ch);
				$data = curl_getinfo($ch);
				$url='{file: "'.$data["redirect_url"].'",label: "360P",type: "video/mp4"}';
		}
		elseif(preg_match('/picasaweb3.google.com/i', $url)) {
			$url=str_replace('?noredirect=1','',$url);
			$url=str_replace('picasaweb3.google.com','picasaweb.google.com',$url);
			$datax=curl($url);
			$i=explode('/',$url);
			$u=explode("var _album = {id:'",$datax);
			$u=explode("'",$u[1]);
			$au=explode('authkey=',$url);
			if(isset($au[1])) $aut='&authkey='.$au[1];
			else $aut='';
			$origin_url='https://picasaweb.google.com/data/feed/tiny/user/'.$i[3].'/albumid/'.$u[0].'?alt=jsonm&kind=photo'.$aut;
			$data=curl($origin_url);
			preg_match_all('#"type":"image/(.*?)"},{"url":"(.*?)","height":(.*?),"type":"video/mpeg4"},{"url":"(.*?)","height":720#',$data,$match);
			$sc='';
			for($i=0;$i<=count($match[0])-1;$i++){
				if(!isset($match[4][$i])) $match720='';
				else $match720='{file: "'.$match[4][$i].'",label: "720P",type: "video/mp4"}';
				$sc .= '{sources: [{file: "'.$match[2][$i].'",label: "360P",type: "video/mp4"},'.$match720.']},';
			}
			$url=$sc;
		}
	return $url;
}
function getJson($xml_link){
    $sourceJson = curl($xml_link);
    $decodeJson = json_decode($sourceJson);
    return $decodeJson->feed->media->content;
}
function getJsontvzing($xml_link){
    $sourceJson = curl($xml_link);
    $decodeJson = json_decode($sourceJson);
    return $decodeJson->response;
}
