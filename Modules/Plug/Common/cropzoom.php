<?php
/*
* cropzoom 图片剪切相关的函数
*/
function determineImageScale($sourceWidth, $sourceHeight, $targetWidth, $targetHeight) {
	$scalex =  $targetWidth / $sourceWidth;
	$scaley =  $targetHeight / $sourceHeight;
	return min($scalex, $scaley);
}

function returnCorrectFunction($ext){
	$function = "";
	switch($ext){
		case "png":
			$function = "imagecreatefrompng";
			break;
		case "jpeg":
			$function = "imagecreatefromjpeg";
			break;
		case "jpg":
			$function = "imagecreatefromjpeg";
			break;
		case "gif":
			$function = "imagecreatefromgif";
			break;
	}
	return $function;
}

function parseImage($ext,$img){
	switch($ext){
		case "png":
			return imagepng($img);
			break;
		case "jpeg":
			return imagejpeg($img);
			break;
		case "jpg":
			return imagejpeg($img);
			break;
		case "gif":
			return imagegif($img);
			break;
	}
}

function setTransparency($imgSrc,$imgDest,$ext){

	if($ext == "png" || $ext == "gif"){
		$trnprt_indx = imagecolortransparent($imgSrc);
		// If we have a specific transparent color
		if ($trnprt_indx >= 0) {
			// Get the original image's transparent color's RGB values
			$trnprt_color    = imagecolorsforindex($imgSrc, $trnprt_indx);
			// Allocate the same color in the new image resource
			$trnprt_indx    = imagecolorallocate($imgDest, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			// Completely fill the background of the new image with allocated color.
			imagefill($imgDest, 0, 0, $trnprt_indx);
			// Set the background color for new image to transparent
			imagecolortransparent($imgDest, $trnprt_indx);
		}
		// Always make a transparent background color for PNGs that don't have one allocated already
		elseif ($ext == "png") {
			// Turn off transparency blending (temporarily)
			imagealphablending($imgDest, true);
			// Create a new transparent color for image
			$color = imagecolorallocatealpha($imgDest, 0, 0, 0, 127);
			// Completely fill the background of the new image with allocated color.
			imagefill($imgDest, 0, 0, $color);
			// Restore transparency blending
			imagesavealpha($imgDest, true);
		}

	}
}

?>
