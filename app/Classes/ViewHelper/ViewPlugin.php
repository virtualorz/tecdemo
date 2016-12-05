<?php

namespace App\Classes\ViewHelper;

use App;

class ViewPlugin {

    private $loadedPlugin = array();
    private $loadedCss = array();
    private $loadedJs = array();
    
    public function load($name){
        $pluginName = strtolower(trim($name));
        $method = '_plugin_' . $pluginName;
        
        if($this->isLoaded($pluginName)){
            return;
        }
        
        if (method_exists($this, $method)) {
            $this->$method();
        }
        $this->loadedPlugin[] = $pluginName;
    }
    
    public function isLoaded($name) {
        return in_array($name, $this->loadedPlugin);
    }
    
    public function renderCss() {
        $html = implode("\n", $this->loadedCss);
        $this->loadedCss = array();
        
        return $html;
    }
    
    public function renderJs() {
        $html = implode("\n", $this->loadedJs);
        $this->loadedJs = array();
        
        return $html;
    }
    
    ##

    private function _plugin_jquery() {
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery/jquery.min.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery/jquery_extend.js') . '"></script>';
    }
    
    private function _plugin_jqueryui() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jquery_ui/css/jquery-ui.min.css') . '" />';
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jquery_ui/css/fix.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_ui/jquery-ui.min.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_ui/datepicker_default.js') . '"></script>';
    }

    private function _plugin_bootstrap() {
        //$this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/bootstrap/css/bootstrap.min.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/bootstrap/js/bootstrap.min.js') . '"></script>';
    }
    
    private function _plugin_json() {
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/json/json2.js') . '"></script>';
    }

    private function _plugin_jqueryvalidation() {
        $this->load('jqueryform');
        
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jquery_validation/jquery.validate.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_validation/jquery.validate.min.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_validation/additional-methods.min.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_validation/default.js') . '"></script>';
                
        $pluginLocale = App::getLocale();
        if($pluginLocale == "en"){
            return;
        }
        switch ($pluginLocale) {
        }
        if (!is_null($pluginLocale)) {
            $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_validation/localization/messages_' . $pluginLocale .  '.min.js') . '"></script>';
        }        
    }

    private function _plugin_jqueryform() {
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_form/jquery.form.min.js') . '"></script>';
    }

    private function _plugin_jqueryfileupload() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jquery_file_upload/jqfu.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_file_upload/js/vendor/jquery.ui.widget.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_file_upload/js/jquery.fileupload.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_file_upload/js/jquery.iframe-transport.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jquery_file_upload/jqfu.js') . '"></script>';
    }

    private function _plugin_superfish() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/superfish/superfish.css') . '" />';
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/superfish/superfish-vertical.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/superfish/superfish.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/superfish/hoverIntent.js') . '"></script>';
    }

    private function _plugin_ckeditor() {
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/ckeditor/ckeditor.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/btseditor/config_btseditor.js') . '"></script>';
    }

    private function _plugin_colorbox() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/colorbox/colorbox.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/colorbox/jquery.colorbox-min.js') . '"></script>';
    }

    private function _plugin_fancybox() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/fancybox/source/jquery.fancybox.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/fancybox/source/jquery.fancybox.pack.js') . '"></script>';
    }

    private function _plugin_btseditor() {
        $this->load('jqueryfileupload');
        $this->load('ckeditor');
        
        
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/btseditor/btseditor.css') . '" />';
        //$this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jquery_file_upload/jqfu_btseditor.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/btseditor/jqfu_btseditor.js') . '"></script>';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/btseditor/btseditor.js') . '"></script>';
        
    }

    private function _plugin_jstree() {
        $this->loadedCss[] = '<link rel="stylesheet" type="text/css" href="' . asset('plugins/jstree/dist/themes/default/style.min.css') . '" />';
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jstree/dist/jstree.min.js') . '"></script>';
    }

    private function _plugin_jscookie() {
        $this->loadedJs[] = '<script type="text/javascript" src="' . asset('plugins/jscookie/js.cookie.js') . '"></script>';
    }

}
