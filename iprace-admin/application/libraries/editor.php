<?php
class Editor{
function geteditor($id,$toolbar="Full"){
		return array(
 
			//ID of the textarea that will be replaced
			'id' 	=> 	$id,
			'path'	=>	'/js/plugins/ckeditor',
			
 
			//Optionnal values
			'config' => array(
				'toolbar' 	=> 	$toolbar, 	//Using the Full toolbar
				'width' 	=> 	"100%",	//Setting a custom width
				'height' 	=> 	'100px',	//Setting a custom height
				 'filebrowserBrowseUrl'      => base_url().'js/plugins/ckeditor/ckfinder/ckfinder.html',
                'filebrowserImageBrowseUrl' => base_url().'js/plugins/ckeditor/ckfinder/ckfinder.html?Type=Images',
                'filebrowserFlashBrowseUrl' => base_url().'js/plugins/ckeditor/ckfinder/ckfinder.html?Type=Flash',
                'filebrowserUploadUrl'      => base_url().'js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                'filebrowserImageUploadUrl' => base_url().'js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                'filebrowserFlashUploadUrl' => base_url().'js/plugins/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
 
			),
 
			//Replacing styles from the "Styles tool"
			'styles' => array(
 
				//Creating a new style named "style 1"
				'style 1' => array (
					'name' 		=> 	'Blue Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 	=> 	'Blue',
						'font-weight' 	=> 	'bold'
					)
				),
 
				//Creating a new style named "style 2"
				'style 2' => array (
					'name' 	=> 	'Red Title',
					'element' 	=> 	'h2',
					'styles' => array(
						'color' 		=> 	'Red',
						'font-weight' 		=> 	'bold',
						'text-decoration'	=> 	'underline'
					)
				)				
			)
		);					
	}
}
?>