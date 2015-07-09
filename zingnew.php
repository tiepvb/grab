function getJsontvzing($xml_link){
    $sourceJson = @file_get_contents($xml_link);
    $decodeJson = json_decode($sourceJson);
    return $decodeJson->response;
}
if(preg_match('/tv.zing.vn/i', $url) || preg_match('/m.tv.zing.vn/i', $url)) {
  $i=explode('/',$url);
  $i[5]=str_replace('.html','',$i[5]);
  $origin_url='http://api.tv.zing.vn/2.0/media/info?api_key=d04210a70026ad9323076716781c223f&media_id='.$i[5];
  $obj_array=getJsontvzing($origin_url);
  if(!empty($obj_array->hls->Video360)) {
  	if(!empty($obj_array->hls->Video720)) $url='{file: "http://'.$obj_array->hls->Video720.'"}';
  	elseif(!empty($obj_array->hls->Video480)) $url='{file: "http://'.$obj_array->hls->Video480.'"}';
  	else $url='{file: "http://'.$obj_array->hls->Video360.'"}';
  }
  else {
  	if(!empty($obj_array->other_url->Video720)) $url='{file: "http://'.$obj_array->other_url->Video480.'",label: "360P", type: "video/mp4"},{file: "http://'.$obj_array->other_url->Video720.'",label: "720P", type: "video/mp4", "default": "true"}';
  	if(!empty($obj_array->other_url->Video480)) $url='{file: "http://'.$obj_array->file_url.'",label: "360P", type: "video/mp4"},{file: "http://'.$obj_array->other_url->Video480.'",label: "720P", type: "video/mp4", "default": "true"}';
  	else $url='{file: "http://'.$obj_array->file_url.'",label: "360P", type: "video/mp4"}';
  }
}
