<?php

namespace App\Classes\ViewHelper;


class ViewHelper {
    use \App\Classes\Youtube\Youtube;
    use \App\Classes\ViewHelper\ViewButton;

    private $plugin;
    
    public function __construct() {
        $this->plugin = new ViewPlugin();
    }
    
    public function plugin() {
        return $this->plugin;
    }
    
    public function youtubeIframe($str, $width = 640, $height = 360, $option = []){
		$url = $this->getYoutubeEmbededUrl($str);
		
		$html = '<iframe width="' . $width . '" height="' . $height . '" src="' . $url . '" frameborder="0" allowfullscreen></iframe>';
		return $html;
    }
    
    public function youtubeThumb($str, $size = "default") {		
		$url = $this->getYoutubeThumbUrl($str, $size);
        
		$html = '<img src="' . $url . '" />';
		return $html;
	}
    
    public function button($name, $param = []) {
        $html = $this->getButton($name, $param);
        
        return $html;
    }

}
