/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = 'MyToolbar';
 	config.extraPlugins = 'MediaEmbed';
	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'NewPage','Preview' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'
                 ,'Iframe' ] },
                '/',
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] }
	];
	
	config.filebrowserBrowseUrl = '/components/kcfinder/browse.php?type=files';
   	config.filebrowserImageBrowseUrl = '/components/kcfinder/browse.php?type=images';
   	config.filebrowserFlashBrowseUrl = '/components/kcfinder/browse.php?type=flash';
   	config.filebrowserUploadUrl = '/components/kcfinder/upload.php?type=files';
   	config.filebrowserImageUploadUrl = '/components/kcfinder/upload.php?type=images';
   	config.filebrowserFlashUploadUrl = '/components/kcfinder/upload.php?type=flash';
};
