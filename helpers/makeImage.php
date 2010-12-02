<?php
class makeImage
{
	private $_file;
	private $_image;
	private $_info;
	private $_width;
	private $_height;
	private $_cache;
	
	public function __construct($sFile, $sCache = false) {
		ini_set("memory_limit", "200m");
		
		$this->_file = $sFile;
		$this->_cache = $sCache;
		
		// Die if cached
		if($this->_cache == true) {
			if ($_SERVER["HTTP_IF_MODIFIED_SINCE"] == gmdate("D, d M Y H:i:s", filemtime($sFile))." GMT"
			 && $_SERVER["HTTP_IF_NONE_MATCH"] == md5($this->_file)) {
				header('HTTP/1.1 304 Not Modified');
				header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($this->_file))." GMT");
				header("Pragma: public");
				header("Cache-Control: maxage=".(60*60*24*2));
				header("Expires: ".gmdate("D, d M Y H:i:s", strtotime("+2 days"))." GMT");
				header("ETag: ".md5($this->_file));
				exit;
			}
		}
		
		// Set image info
		$this->_info = getimagesize($sFile);
		list($this->_width, $this->_height) = $this->_info;
		
		// Load image
		switch($this->_info[2]) {
			case IMAGETYPE_GIF: $this->_image = imagecreatefromgif($sFile); break;
			case IMAGETYPE_JPEG: $this->_image = imagecreatefromjpeg($sFile); break;
			case IMAGETYPE_PNG: $this->_image = imagecreatefrompng($sFile); break;
			default: return false;
	    }
	}
	public function crop($sPhotoWidth, $sPhotoHeight, $sPhotoX1, $sPhotoY1) {
		if(!empty($this->_info) && !empty($this->_image)) {
			// Load end canvas for cropping
			$oImage = imagecreatetruecolor($sPhotoWidth, $sPhotoHeight);
			imagefill($oImage, 0, 0, imagecolorallocate($oImage, 255, 255, 255));
			
			// Crop image onto canvas
			imagecopyresized($oImage, $this->_image, 0, 0, $sPhotoX1, $sPhotoY1, $this->_width, $this->_height, $this->_width, $this->_height);
			
			// Save info
			$this->_image = $oImage;
			$this->_width = imageSX($this->_image);
			$this->_height = imageSY($this->_image);
			
			unset($oImage);
			
			return true;
		} else
			return false;
	}
	public function cropCenter($sWidth, $sHeight) {
		$sSourceAspectRatio = $this->_width / $this->_height;
		$sDesiredAspectRatio = $sWidth / $sHeight;

		if ( $sSourceAspectRatio > $sDesiredAspectRatio ) {
			$sTempHeight = $sHeight;
			$sTempWidth = (int)($sHeight * $sSourceAspectRatio);
		} else {
			$sTempWidth = $sWidth;
			$sTempHeight = (int)($sWidth / $sSourceAspectRatio);
		}
		
		$oTempImage = imagecreatetruecolor($sTempWidth, $sTempHeight);
		imagecopyresampled($oTempImage, $this->_image, 0, 0, 0, 0, $sTempWidth, $sTempHeight, $this->_width, $this->_height);
		
		$x0 = ( $sTempWidth - $sWidth ) / 2;
		$y0 = ( $sTempHeight - $sHeight ) / 2;
		
		$oImage = imagecreatetruecolor($sWidth, $sHeight);
		imagecopy($oImage, $oTempImage, 0, 0, $x0, $y0, $sWidth, $sHeight);
		
		// Save info
		$this->_image = $oImage;
		$this->_width = imageSX($this->_image);
		$this->_height = imageSY($this->_image);
		
		unset($oImage);
		unset($oTempImage);
		
		return true;
	}
	public function resizeWidth($sWidth) {
		$sRatio = (int)$sWidth / $this->_width;
		$sHeight = $this->_height * $sRatio;
		
		return $this->resize($sWidth, $sHeight);
	}
	public function resizeHeight($sHeight) {
		$sRatio = (int)$sHeight / $this->_height;
		$sWidth = $this->_width * $sRatio;
		
		return $this->resize($sWidth, $sHeight);
	}
	public function scale($sScale) {
		$sWidth = $this->_width * (int)$sScale / 100;
		$sHeight = $this->_height * (int)$sScale / 100;
		
		return $this->resize($sWidth, $sHeight);
	}
	public function resize($sWidth, $sHeight) {
		if(!empty($this->_info) && !empty($this->_image)) {
			// Create resized canvas
			$oResized = imagecreatetruecolor($sWidth, $sHeight);
			imagefill($oResized, 0, 0, imagecolorallocate($oResized, 255, 255, 255));
			
			// Resize image
			imagecopyresampled($oResized, $this->_image, 0, 0, 0, 0, $sWidth, $sHeight, $this->_width, $this->_height);
			
			// Save info
			$this->_image = $oResized;
			$this->_width = imageSX($this->_image);
			$this->_height = imageSY($this->_image);
			
			unset($oResized);
			
			return true;
		} else
			return false;
	}
	public function text($sText, $sFont, $sSize = 10, $sAngle = 0, $sX = 0, $sY = 10, $aColor = array(0, 0, 0, 0)) {
		if(!empty($this->_info) && !empty($this->_image)) {
			$oColor = imagecolorallocatealpha($this->_image, $aColor[0], $aColor[1], $aColor[2], $aColor[3]);
			
			imagettftext($this->_image, $sSize, $sAngle, $sX, $sY, $oColor, $sFont, $sText);
			
			unset($oColor);
			
			return true;
		} else
			return false;
	}
	public function overlay($sType,
		$sX1 = 0, $sY1 = 0,
		$sX2 = 1, $sY2 = 1,
		$aColor = array(0, 0, 0, 0),
		$aPoints = array(0,0,10,10,0,10)
	) {
		if(!empty($this->_info) && !empty($this->_image)) {
			$oColor = imagecolorallocatealpha($this->_image, $aColor[0], $aColor[1], $aColor[2], $aColor[3]);
			
			switch($sType) {
				case "rectangle":
					imagerectangle($this->_image, $sX1, $sY1, $sX2, $sY2, $oColor);
					break;
				case "ellipse":
					imageellipse($this->_image, $sX1, $sY1, $sX2, $sY2, $oColor);
					break;
				case "polygon":
					if(count($aPoints) >=  6)
						imagepolygon($this->_image, $aPoints, (count($aPoints) / 2), $oColor);
					else
						return false;
					break;
				default:
					return false;
			}
			
			unset($oColor);
			
			return true;
		} else
			return false;
	}
	public function overlay_filled($sType,
		$sX = 0, $sY = 0,
		$sWidth = 1, $sHeight = 1,
		$aColor = array(0, 0, 0, 0),
		$aPoints = array(0,0,10,10,0,10)
	) {
		if(!empty($this->_info) && !empty($this->_image)) {
			$oColor = imagecolorallocatealpha($this->_image, $aColor[0], $aColor[1], $aColor[2], $aColor[3]);
			
			switch($sType) {
				case "rectangle":
					imagefilledrectangle($this->_image, $sX, $sY, $sWidth, $sHeight, $oColor);
					break;
				case "ellipse":
					imagefilledellipse($this->_image, $sX, $sY, $sWidth, $sHeight, $oColor);
					break;
				case "polygon":
					if(count($aPoints) >=  6)
						imagefilledpolygon($this->_image, $aPoints, (count($aPoints) / 2), $oColor);
					else
						return false;
					break;
				default:
					return false;
			}
			
			unset($oColor);
		
			return true;
		}
		else
			return false;
	}
	public function draw($sFile = null, $sQuality = 85) {
		if(!empty($this->_info) && !empty($this->_image)) {
			if(empty($sFile)) {
				$mime = image_type_to_mime_type($this->_info[2]);
				header("Content-type: $mime");
				header("Last-Modified: ".gmdate("D, d M Y H:i:s", filemtime($this->_file))." GMT");
				
				if($this->_cache == true) {
					header("Pragma: public");
					header("Cache-Control: maxage=".(60*60*24*2));
					header("Expires: ".gmdate("D, d M Y H:i:s", strtotime("+2 days"))." GMT");
					header("ETag: ".md5($this->_file));
				}
				
				flush();
	 		}
			
			$this->transparency();
			
			switch($this->_info[2]) {
				case IMAGETYPE_JPEG: imagejpeg($this->_image, $sFile, $sQuality); break;
				case IMAGETYPE_PNG: imagepng($this->_image, $sFile); break;
				case IMAGETYPE_GIF: imagegif($this->_image, $sFile); break;
				default: return false;
			}
			
			imagedestroy($this->_image);
			
			return true;
		} else
			return false;
	}
	protected function transparency() {
		if ( ($this->_info[2] == IMAGETYPE_GIF) || ($this->_info[2] == IMAGETYPE_PNG) ) {
			$trnprt_indx = imagecolortransparent($this->_image);
 
			// If we have a specific transparent color
			if ($trnprt_indx >= 0) {
				$trnprt_color    = imagecolorsforindex($this->_image, $trnprt_indx);
				$trnprt_indx    = imagecolorallocate($this->_image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
				imagefill($this->_image, 0, 0, $trnprt_indx);
				imagecolortransparent($this->_image, $trnprt_indx);
			}
			// Always make a transparent background color for PNGs that don't have one allocated already
			elseif ($this->_info[2] == IMAGETYPE_PNG) {
				imagealphablending($this->_image, false);
				$color = imagecolorallocatealpha($this->_image, 0, 0, 0, 127);
				imagefill($this->_image, 0, 0, $color);
				imagesavealpha($this->_image, true);
			}
		}
	}
}