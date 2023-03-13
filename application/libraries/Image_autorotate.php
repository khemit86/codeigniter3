<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* @file application/libraries/Image_autorotate.php
*/
class Image_autorotate
{
	function __construct($params = NULL) {
		
		if (!is_array($params) || empty($params)) return FALSE;
		
		$filepath = $params['filepath'];
		$exif = @exif_read_data($filepath);
		
		if (empty($exif['Orientation'])) return FALSE;
		
		$CI =& get_instance();
		$CI->load->library('image_lib');
		
		$config['image_library'] = 'gd2';
		$config['source_image']	= $filepath;
		
		$oris = array();
		
		switch($exif['Orientation'])
		{
		        case 1: // no need to perform any changes
		        break;
		
		        case 2: // horizontal flip
				$oris[] = 'hor';
		        break;
		                                
		        case 3: // 180 rotate left
		        	$oris[] = '180';
		        break;
		                    
		        case 4: // vertical flip
		        	$oris[] = 'ver';
		        break;
		                
		        case 5: // vertical flip + 90 rotate right
		        	$oris[] = 'ver';
				$oris[] = '270';
		        break;
		                
		        case 6: // 90 rotate right
		        	$oris[] = '270';
		        break;
		                
		        case 7: // horizontal flip + 90 rotate right
		        	$oris[] = 'hor';
				$oris[] = '270';
		        break;
		                
		        case 8: // 90 rotate left
		        	$oris[] = '90';
		        break;
				
			default: break;
		}
		
		foreach ($oris as $ori) {
			$config['rotation_angle'] = $ori;
			$CI->image_lib->initialize($config); 
			$CI->image_lib->rotate();
		}
	}
	public function createThumbnail($filepath, $thumbpath, $thumbnail_width, $thumbnail_height, $background=false) {
		list($original_width, $original_height, $original_type) = getimagesize($filepath);
		if ($original_width > $original_height) {
			$new_width = $thumbnail_width;
			$new_height = intval($original_height * $new_width / $original_width);
		} else {
			$new_height = $thumbnail_height;
			$new_width = intval($original_width * $new_height / $original_height);
		}
		$dest_x = 0;
		$dest_y = 0;
		$ntype = 1;
		if ($original_type === 1) {
			$imgt = "ImageGIF";
			$imgcreatefrom = "ImageCreateFromGIF";
			$ntype = 1;
		} else if ($original_type === 2) {
			$imgt = "ImageJPEG";
			$ntype = 2;
			$imgcreatefrom = "ImageCreateFromJPEG";
		} else if ($original_type === 3) {
			$imgt = "ImagePNG";
			$ntype = 3;
			$imgcreatefrom = "ImageCreateFromPNG";
		} else {
			return false;
		}

		$old_image = $imgcreatefrom($filepath);
		$new_image = imagecreatetruecolor($new_width, $new_height); // creates new image, but with a black background

		imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
		//$imgt($new_image, $thumbpath);
		
		 switch ($ntype) {  
            case 1:  
            case 2:  
            imagejpeg($new_image,$thumbpath,99);  
            break;  
             
            case 3:  
            imagepng($new_image,$thumbpath);  
            break;  
        }  
		
		
		return file_exists($thumbpath);
	}
}

// END class Image_autorotate

/* End of file Image_autorotate.php */
/* Location: ./application/libraries/Image_autorotate.php */