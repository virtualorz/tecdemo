CKEDITOR.editorConfig = function (config) {
    config.toolbar =
            [
                ['Undo', 'Redo'],
                ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'],
                ['NumberedList','BulletedList','Outdent','Indent','Blockquote','JustifyLeft', 'JustifyCenter', 'JustifyRight'],
                ['Link', 'Unlink','Table','HorizontalRule','Smiley','SpecialChar'],
                ['Styles','Font', 'FontSize', 'TextColor', 'BGColor'],
            ];

    config.defaultLanguage = 'zh';
    config.enterMode = CKEDITOR.ENTER_BR;
    config.fillEmptyBlocks = false;
    config.startupFocus = false;
    config.tabSpaces = 4;
    config.extraPlugins = 'divarea'
    //config.extraPlugins = 'sharedspace'	
    //config.removePlugins = 'liststyle,tabletools,maximize,resize,elementspath';
    //config.removePlugins = 'liststyle,tabletools,contextmenu,maximize,resize,elementspath';



    config.pasteFromWordRemoveFontStyles = false;
    config.pasteFromWordRemoveStyles = false;

    config.font_names = '標楷體;新細明體;微軟正黑體;' + config.font_names;

    CKEDITOR.config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;13/13px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px';

    CKEDITOR.config.coreStyles_bold = {
        element: 'strong',
        styles: {'font-weight': 'bold'}
    };
    CKEDITOR.config.coreStyles_italic = {
        element: 'em',
        styles: {'font-style': 'italic'}
    };
    CKEDITOR.config.coreStyles_strike = {
        element: 's',
        styles: {'text-decoration': 'line-through'}
    };
    CKEDITOR.config.coreStyles_underline = {
        element: 'u',
        styles: {'text-decoration': 'underline'}
    };

//    config.filebrowserBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=files';
//    config.filebrowserImageBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=images';
//    config.filebrowserFlashBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=flash';
//    config.filebrowserUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=files';
//    config.filebrowserImageUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=images';
//    config.filebrowserFlashUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=flash';
};
  