<?php

namespace App\Classes\Youtube;


trait Youtube {

    public function getYoutubeId($str) {
		$id = "";
		$str = preg_replace("/(>|<)/i", '', $str);
		$str = preg_split("/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/", $str);
		if (isset($str[1])) {
			$id = preg_split("/[^0-9a-z_\-]/i", $str[1]);
			$id = $id[0];
		} else {
			$id = $str[0];
		}

		return $id;
	}
    
    public function getYoutubeEmbededUrl($str) {
		if (strlen(trim($str)) <= 0)
			return "";
		$id = $this->getYoutubeId($str);
		if ($id == "")
			return "";
		
		$url = "https://www.youtube.com/embed/" . $id;

		return $url;
	}
    
    public function getYoutubeThumbUrl($str, $size = "default") {
		if (strlen(trim($str)) <= 0)
			return "";

		$id = $this->getYoutubeId($str);
		if ($id == "")
			return "";
		
		$url = "https://i1.ytimg.com/vi/" . $id . "/" . $size . ".jpg";
		return $url;
	}

}
