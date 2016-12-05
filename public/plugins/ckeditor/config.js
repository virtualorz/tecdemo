/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar =
			[
				['Source','Save','NewPage','DocProps','Preview','Print','Templates'],
				['Cut','Copy','Paste','PasteText','PasteFromWord','Undo','Redo'],
				['Find','Replace','SelectAll','SpellChecker','Scayt'],
				['Bold','Italic','Underline','Strike','Subscript','Superscript','RemoveFormat'],
				['NumberedList','BulletedList','Outdent','Indent','Blockquote','CreateDiv','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','BidiLtr','BidiRtl'],
				['Link','Unlink','Anchor'],
				['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
				['Styles','Format','Font','FontSize'],
				['TextColor','BGColor'],
				['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField' ],
				['Maximize','ShowBlocks','About']
			];

	config.defaultLanguage = 'zh';
	config.enterMode = CKEDITOR.ENTER_BR;
	config.fillEmptyBlocks = false;
	config.width = '100%';
	
	config.pasteFromWordRemoveFontStyles = false;
	config.pasteFromWordRemoveStyles = false;
    
    config.font_names = '標楷體;新細明體;微軟正黑體;' + config.font_names;

	//config.contentsCss = [CKEDITOR.basePath + "mycss.css"];
	//config.docType = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">";
	//config.bodyClass = "content";
	
    config.filebrowserBrowseUrl = CKEDITOR.basePath + '/backend/elfinder/ckeditor';
//	config.filebrowserBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=files';
//	config.filebrowserImageBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=images';
//	config.filebrowserFlashBrowseUrl = CKEDITOR.basePath + '../kcfinder/browse.php?opener=ckeditor&type=flash';
//	config.filebrowserUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=files';
//	config.filebrowserImageUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=images';
//	config.filebrowserFlashUploadUrl = CKEDITOR.basePath + '../kcfinder/upload.php?opener=ckeditor&type=flash';	
};
  