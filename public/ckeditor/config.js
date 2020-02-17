/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	CKEDITOR.on('instanceReady', function (e) {
		var instance = e.editor;
		instance.on("change", function (evt) {
			onCKEditorChange(evt.editor);
		});
		//key event handler is a hack, cause change event doesn't handle interaction with these keys
		instance.on('key', function (evt) {
			var backSpaceKeyCode = 8;
			var deleteKeyCode = 46;
			if (evt.data.keyCode == backSpaceKeyCode || evt.data.keyCode == deleteKeyCode) {
				//timeout needed cause editor data will update after this event was fired
				setTimeout(function() {
					onCKEditorChange(evt.editor);
				}, 100);
			}
		});
		instance.on('mode', function () {
			if (this.mode == 'source') {
				var editable = instance.editable();
				editable.attachListener(editable, 'input', function (evt) {
					onCKEditorChange(instance);
				});
			}
		});
	});

	function onCKEditorChange(instance) {
		instance.updateElement();
		triggerElementChangeAndJqueryValidation($(instance.element.$));
	}

	function triggerElementChangeAndJqueryValidation(element) {
		element.trigger('keyup');
	}
};
