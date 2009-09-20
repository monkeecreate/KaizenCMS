<?php
class image extends appController
{
	### DISPLAY ######################
	function resize()
	{
		$name = $this->_settings->site_root.substr($_GET["file"],1);
		$filename = array_pop(explode("/",$file));
		$new_w = $_GET["width"];
		$new_h = $_GET["height"];
		$system = array_pop(explode('.',$name));
		
		header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($name))." GMT");
		header("Expires: ".gmdate("D, d M Y H:i:s", strtotime("+2 days"))." GMT");
		header("ETag: ".md5($name));
		
		if(preg_match('/jpg|jpeg/',$system))
		{	
			header('Content-Type: image/jpeg');
			$src_img = imagecreatefromjpeg($name);
		}
		elseif(preg_match('/png/',$system))
		{
			header('Content-Type: image/png');
			$src_img = imagecreatefrompng($name);
		}
		elseif(preg_match('/gif/',$system))
		{
			header('Content-Type: image/gif');
			$src_img = imagecreatefromgif($name);
		}
		
		$old_x = imageSX($src_img);
		$old_y = imageSY($src_img);
		if($old_x < $new_w && $old_y < $new_h)
		{
		    $thumb_w = $old_x;
		    $thumb_h = $old_y;
		}
		elseif($old_x > $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		elseif($old_x < $old_y)
		{
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		elseif($old_x == $old_y)
		{
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		
		$dst_img = imagecreatetruecolor($thumb_w,$thumb_h);
		imagefill($dst_img, 0, 0, imagecolorallocate($dst_img, 255, 255, 255));

		if(preg_match("/png|gif/",$system))
			$this->setTransparency($dst_img, $src_img);
	    
	    imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

		if(preg_match("/png/",$system))
			imagepng($dst_img, null, 0);
		elseif(preg_match("/gif/",$system))
			imagegif($dst_img);
		elseif(preg_match("/jpg|jpeg/",$system))
			imagejpeg($dst_img, null, 80);
		
		imagedestroy($dst_img);
		imagedestroy($src_img);
	}
	##################################
	
	### Functions ####################
	protected function cropimage($filename, $source, $end_width, $end_height, $photo_width, $photo_height, $photo_x1, $photo_y1)
	{
		// Get new sizes
		list($width, $height) = getimagesize($filename);

		// Load
		$thumb = imagecreatetruecolor($photo_width, $photo_height);
		imagefill($thumb, 0, 0, imagecolorallocate($thumb, 255, 255, 255));

		// Resize
		imagecopyresized($thumb, $source, 0, 0, $photo_x1, $photo_y1, $width, $height, $width, $height);

		$thumb2 = imagecreatetruecolor($end_width, $end_height);
		imagecopyresized($thumb2, $thumb, 0, 0, 0, 0, $end_width, $end_height, $photo_width, $photo_height);
		
		return $thumb2;
	}
	protected function resizeimage($image)
	{
		$newwidth=$_GET["width"];
		$newheight=($this->height/$this->width)*$newwidth;
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		imagefill($tmp, 0, 0, imagecolorallocate($tmp, 255, 255, 255));
		
		imagecopyresampled($tmp,$image,0,0,0,0,$newwidth,$newheight,$this->width,$this->height);
		
		return $tmp;
	}
	protected function setTransparency($new_image, $image_source)
	{
		$transparencyIndex = imagecolortransparent($image_source);
		$transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);

	    if ($transparencyIndex >= 0)
	    	$transparencyColor = imagecolorsforindex($image_source, $transparencyIndex); 
	    
	    $transparencyIndex = imagecolorallocate($new_image, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
	    imagefill($new_image, 0, 0, $transparencyIndex);
	    imagecolortransparent($new_image, $transparencyIndex);
	}
	##################################
} 