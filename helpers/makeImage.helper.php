<?php
class makeImage
{
	private $_file;
	private $_image;
	private $_info;
	private $_width;
	private $_height;
	private $_cache;
	
	public function __construct($sFile, $sCache = false)
	{
		ini_set("memory_limit", "200m");
		
		$this->_file = $sFile;
		$this->_cache = $sCache;
		
		// Die if cached
		if($this->_cache == true)
		{
			if (isset($aHeaders['If-Modified-Since'])
			 && !empty($aHeaders['If-Modified-Since'])
			 && $aHeaders["If-Modified-Since"] == gmdate("D, d M Y H:i:s", filemtime($sFile))." GMT") {
				header('HTTP/1.1 304 Not Modified');
				exit;
			}
		}
		
		// Set image info
		$this->_info = getimagesize($sFile);
		list($this->_width, $this->_height) = $this->_info;
		
		// Load image
		switch($this->_info[2])
		{
			case IMAGETYPE_GIF: $this->_image = imagecreatefromgif($sFile); break;
			case IMAGETYPE_JPEG: $this->_image = imagecreatefromjpeg($sFile); break;
			case IMAGETYPE_PNG: $this->_image = imagecreatefrompng($sFile); break;
			default: return false;
	    }
	}
	public function crop($sPhotoWidth, $sPhotoHeight, $sPhotoX1, $sPhotoY1)
	{
		if(!empty($this->_info) && !empty($this->_image))
		{
			// Load end canvas for cropping
			$oImage = imagecreatetruecolor($sPhotoWidth, $sPhotoHeight);
			imagefill($oImage, 0, 0, imagecolorallocate($oImage, 255, 255, 255));
			
			// Crop image onto canvas
			imagecopyresized($oImage, $this->_image, 0, 0, $sPhotoX1, $sPhotoY1, $this->_width, $this->_height, $this->_width, $this->_height);
			
			// Save info
			$this->_image = $oImage;
			$this->_width = imageSX($this->_image);
			$this->_height = imageSY($this->_image);
			
			$oImage = null;
			
			return true;
		}
		else
			return false;
	}
	public function resize($sWidth, $sHeight, $sKeep = false)
	{
		if(!empty($this->_info) && !empty($this->_image))
		{
			if($sKeep == false)
			{
				if($sWidth >= $this->_width && $sHeight >= $this->_height)
					return true;// Image smaller than size given
				elseif($this->_width < $sWidth && $this->_height < $sHeight)
				{
					$sNewWidth = $this->_width;
					$sNewHeight = $this->_height;
				}
				elseif($this->_width > $this->_height)
				{
					$sNewWidth = $sWidth;
					$sNewHeight = $this->_height * ($sHeight / $this->_width);
				}
				elseif($this->_width < $this->_height)
				{
					$sNewWidth = $this->_width * ($sWidth / $this->_height);
					$sNewHeight = $sHeight;
				}
				elseif($this->_width == $this->_height)
				{
					$sNewWidth = $sWidth;
					$sNewHeight = $sHeight;
				}
			}
			else
			{
				$sNewWidth = $sWidth;
				$sNewHeight = $sHeight;
			}
			
			// Create resized canvas
			$oResized = imagecreatetruecolor($sNewWidth, $sNewHeight);
			imagefill($oResized, 0, 0, imagecolorallocate($oResized, 255, 255, 255));
			
			// Resize image
			imagecopyresampled($oResized, $this->_image, 0, 0, 0, 0, $sNewWidth, $sNewHeight, $this->_width, $this->_height);
			
			// Save info
			$this->_image = $oResized;
			$this->_width = imageSX($this->_image);
			$this->_height = imageSY($this->_image);
			
			$oResized = null;
			
			return true;
		}
		else
			return false;
	}
	public function draw($sFile = null, $sQuality = 85)
	{
		if(!empty($this->_info) && !empty($this->_image))
		{
			if(empty($sFile))
			{
				$mime = image_type_to_mime_type($this->_info[2]);
				header("Content-type: $mime");
				header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($this->_file))." GMT");
				
				if($this->_cache == true)
				{
					header("Pragma: public");
					header("Cache-Control: maxage=".(60*60*24*2));
					header("Expires: ".gmdate("D, d M Y H:i:s", strtotime("+2 days"))." GMT");
					header("ETag: ".md5($this->_file));
				}
	 		}
			
			$this->transparency();
			
		    switch($this->_info[2])
			{
				case IMAGETYPE_JPEG: imagejpeg($this->_image, $sFile, $sQuality); break;
				case IMAGETYPE_PNG: imagepng($this->_image, $sFile); break;
		 		case IMAGETYPE_GIF: imagegif($this->_image, $sFile); break;
				default: return false;
			}
			
			imagedestroy($this->_image);
			
			return true;
		}
		else
			return false;
	}
	
	protected function transparency()
	{
		if ( ($this->_info[2] == IMAGETYPE_GIF) || ($this->_info[2] == IMAGETYPE_PNG) )
		{
			$trnprt_indx = imagecolortransparent($this->_image);
 
			// If we have a specific transparent color
			if ($trnprt_indx >= 0)
			{
				$trnprt_color    = imagecolorsforindex($this->_image, $trnprt_indx);
				$trnprt_indx    = imagecolorallocate($this->_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($this->_image, 0, 0, $trnprt_indx);
				imagecolortransparent($this->_image, $trnprt_indx);
			}
			// Always make a transparent background color for PNGs that don't have one allocated already
			elseif ($this->_info[2] == IMAGETYPE_PNG)
			{
				imagealphablending($this->_image, false);
				$color = imagecolorallocatealpha($this->_image, 0, 0, 0, 127);
				imagefill($this->_image, 0, 0, $color);
				imagesavealpha($this->_image, true);
			}
		}
	}
}