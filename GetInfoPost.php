<?php
function get_info_post($uid, $token)
{
	$linklist = 'https://graph.facebook.com/'.$uid.'/feed?limit=5000&access_token='.$token;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $linklist);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) coc_coc_browser/68.4.154 Chrome/62.4.3202.154 Safari/537.36');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$data = curl_exec($ch);
	curl_close($ch);

	$infoidpost = json_decode($data);

	$info = array();

	foreach ($infoidpost->data as $value) {
		$public_scope = explode('_', $value->id);
		$created_time = explode('T', $value->created_time);
		$formatdate = explode('-', $created_time[0]);
		$time = explode('+', $created_time[1]);
		$formattime = explode(':', $time[0]);
		$var['id'] = $public_scope[1];
		$var['public_scope'] = $value->id;
		if (isset($value->story) && $value->story) {
			$var['story'] = $value->story;
		}
		else {
			$var['story'] = 'Không có tiêu đề';
		}
		if (isset($value->likes) && $value->likes) {
			$var['like'] = $value->likes->count;
		}
		else {
			$var['like'] = 0;
		}
		$var['comment'] = $value->comments->count;
		if (isset($value->shares) && $value->shares) {
			$var['share'] = $value->shares->count;
		}
		else {
			$var['share'] = 0;
		}
		$var['time'] = ($formattime[0] - 5).':'.$formattime[1].':'.$formattime[2];
		$var['date'] = $formatdate[2].'/'.$formatdate[1].'/'.$formatdate[0];
		array_push($info, $var);
	}
	return $info;

}