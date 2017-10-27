<?php

function SendRequest($url, $post, $post_data, $user_agent, $cookies) {
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://instagram.com/api/v1/'.$url);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Language: id'));
	
	if($post) {
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	}
		
	if($cookies) {
		curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
	} else {
		curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
	}
		
	$response = curl_exec($ch);
	$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
		
	return array($http, $response);
}

function GenerateGuid() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(16384, 20479), 
			mt_rand(32768, 49151), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535), 
			mt_rand(0, 65535));
}
function GenerateUserAgent() {	
	$resolutions = array('720x1280', '320x480', '480x800', '1024x768', '1280x720', '768x1024', '480x320');
	$versions = array('GT-N7000', 'SM-N9000', 'GT-I9220', 'GT-I9100');
	$dpis = array('120', '160', '320', '240');

	$ver = $versions[array_rand($versions)];
	$dpi = $dpis[array_rand($dpis)];
	$res = $resolutions[array_rand($resolutions)];
	
	return 'Instagram 4.'.mt_rand(1,2).'.'.mt_rand(0,2).' Android ('.mt_rand(10,11).'/'.mt_rand(1,3).'.'.mt_rand(3,5).'.'.mt_rand(0,5).'; '.$dpi.'; '.$res.'; samsung; '.$ver.'; '.$ver.'; smdkc210; en_US)';
}

function GenerateSignature($data) {
	return hash_hmac('sha256', $data, 'b4a23f5e39b5929e0666ac5de94c89d1618a2916');
}

function GetPostData_profil($filename) {
	if(!$filename) {
		echo "The image doesn't exist ".$filename;
	} else {
		$post_data = array('profile_pic' => '@'.$filename);
		return $post_data;
	}
}

function GetPostData($filename) {
	if(!$filename) {
		echo "The image doesn't exist ".$filename;
	} else {
		$post_data = array('device_timestamp' => time(), 
							'photo' => '@'.$filename);
		return $post_data;
	}
}

$agent = GenerateUserAgent();
$guid = GenerateGuid();
$device_id = "android-".$guid;

function Gid($next_max_id = null, $i = 1)
{
	global $agent, $ID;
	$action = 'following'; 
	$following = SendRequest('friendships/'.$ID.'/'.$action.'?max_id='.$next_max_id, false, false, $agent, true);
	$obj = json_decode($following[1]);
	@$max_id = $obj->next_max_id;
	foreach ((array)$obj->users as $users) {
		$user_id = $users->pk;
		$username_ = $users->username;
		$check = SendRequest('friendships/show/'.$user_id.'/', false, false, $agent, true);
		$cek = json_decode($check[1]);
		if ($cek->followed_by == false) {
			echo $i . ". @" . $username_ . " belum follback kamu.<br/>";
			$action = 'destroy'; 
			$data = '{"user_id":"'.$user_id.'"}';
			$sig = GenerateSignature($data);
			$new_data = 'signed_body='.$sig.'.'.urlencode($data).'&ig_sig_key_version=4';
			$follow = SendRequest('friendships/'.$action.'/'.$user_id.'/', true, $new_data, $agent, true);
			$obj2 = json_decode($follow[1]);
			if ($obj2->status == "ok") {
				echo "-- Mengunfollow @" . $username_ . " Sukses<br/>";
				sleep(1);
			}
			$i++;
			flush();
			ob_flush();
		} else {
			echo "@" . $username_ . " saling follow dengan anda :D<br/>";
			flush();
			ob_flush();
		}
	}
	if($max_id) {
		notFollback($max_id, $i);
		exit();
	}
	$i--;
	echo "$i yang belum follback sudah di unfollow<br/>";
}
