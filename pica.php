$origin_url='https://picasaweb.google.com/data/feed/tiny/user/{userID}/albumid/{albumID}?alt=jsonm&kind=photo&authkey={authkey};
			$data=curl($origin_url);
			//preg_match_all('#"type":"image/(.*?)"},{"url":"(.*?)","height":(.*?),"type":"video/mpeg4"},{"url":"(.*?)","height":720#',$data,$match);
			preg_match_all('#"media":{"content"(.*?)"description"#',$data,$match);
			$sc='';
			for($i=0;$i<=count($match[0])-1;$i++){
				$Z360P = explode('image/gif"},{"url":"', $match[1][$i]);
				$Z360P = explode('","height"', $Z360P[1]);
				$Z720P = explode('","height":720', $match[1][$i]);
				$Z720P = explode('video/mpeg4"},{"url":"', $Z720P[0]);
				if(!isset($Z720P[1])) $Z720='';
				else $Z720='{file: "'.$Z720P[1].'",label: "720P",type: "video/mp4","default": "true"}';;
				$c=$i+1;
				//if(!isset($match[4][$i])) $match720='';
				//else $match720='{file: "'.$match[4][$i].'",label: "720P",type: "video/mp4","default": "true"}';
				$sc .= '{sources: [{file: "'.$Z360P[0].'",label: "360P",type: "video/mp4"},'.$Z720.'],title: "Phần '.$c.'"},';
			}
			$url=$sc;
