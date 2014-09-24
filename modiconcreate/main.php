<?php

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

$decoded = json_decode(file_get_contents("thing.json"), true);
// $font = "/home/avail/www/somethinghere/fonts/Dosis-Regular.ttf"; // full path needed on linux or gdlib won't recognize the font
$font = "fonts/Dosis-Regular.ttf";

function main() {
	global $decoded, $font;
	if(!is_dir("img/"))
		mkdir("img/");
	for( $i = 0; $i < count($decoded["images"]); $i++) {
		createPicture($i);
		createPicture2x($i);
	}
}

function createPicture( $number ) {
	global $decoded, $font;
	$varBG = $decoded["images"][$number]["background"];
	$varFG = $decoded["images"][$number]["foreground"];
	$varText = $decoded["images"][$number]["text"];
	$varPath = "img/" . $decoded["images"][$number]["name"] . ".png";

	list($bg1, $bg2, $bg3) = explode(", ", $varBG);
	list($fg1, $fg2, $fg3) = explode(", ", $varFG);

	$backgroundImage = imagecreatetruecolor($decoded["size_x"], $decoded["size_y"]);
	$backgroundColour = imagecolorallocate($backgroundImage, $bg1, $bg2, $bg3);
	$foregroundColour = imagecolorallocate($backgroundImage, $fg1, $fg2, $fg3);

	imagesavealpha($backgroundImage, true);
	imagefill($backgroundImage, 0, 0, $backgroundColour);

	imagettftext($backgroundImage, 25, 0, centerText($varText), ($decoded["size_x"] - 25), $foregroundColour, $font, $varText);
	imagefilledrectangle($backgroundImage, resizeUnderscore($varText), 42, ($decoded["size_x"] - resizeUnderscore($varText)), ($decoded["size_y"] - 23), $foregroundColour);

	imagepng($backgroundImage, $varPath);
	print("Creating " . $varPath);
}

function createPicture2x( $number ) {
	global $decoded, $font;
	$varBG = $decoded["images"][$number]["background"];
	$varFG = $decoded["images"][$number]["foreground"];
	$varText = $decoded["images"][$number]["text"];
	$varPath = "img/" . $decoded["images"][$number]["name"] . "@2x.png";

	list($bg1, $bg2, $bg3) = explode(", ", $varBG);
	list($fg1, $fg2, $fg3) = explode(", ", $varFG);

	$backgroundImage = imagecreatetruecolor($decoded["size2_x"], $decoded["size2_y"]);
	$backgroundColour = imagecolorallocate($backgroundImage, $bg1, $bg2, $bg3);
	$foregroundColour = imagecolorallocate($backgroundImage, $fg1, $fg2, $fg3);

	imagesavealpha($backgroundImage, true);
	imagefill($backgroundImage, 0, 0, $backgroundColour);

	imagettftext($backgroundImage, 55, 0, centerText($varText, 2), ($decoded["size2_x"] - 48), $foregroundColour, $font, $varText);
	imagefilledrectangle($backgroundImage, resizeUnderscore($varText, 2), 88, ($decoded["size2_x"] - resizeUnderscore($varText, 2)), ($decoded["size2_y"] - 42), $foregroundColour);

	imagepng($backgroundImage, $varPath);
	print("Creating " . $varPath);
}

function calculatex( $text, $size = 0) {
	if ( $size == 2) {
		switch(strlen($text)) {
			case 3:
				return 3;
				break;
			case 2:
				return 30;
				break;
		}
	} else {
		switch(strlen($text)) {
			case 3:
				return 3;
				break;
			case 2:
				return 15;
				break;
		}
	}
}

// http://stackoverflow.com/questions/15982732/php-gd-align-text-center-horizontally-and-decrease-font-size-to-keep-it-inside
function centerText( $text, $size = 0) {
	global $font;

	if($size == 2) {
		$img_width = 128;
		$img_height = 128;
		$font_size = 55;
	} else {
		$img_width = 64;
		$img_height = 64;
		$font_size = 25;
	}
	$tmpWidth = 0.32;

	$txt_max_width = intval($tmpWidth * $img_width);

	do {

	    $font_size++;
	    $p = imagettfbbox($font_size, 0, $font, $text);
	    $txt_width = $p[2] - $p[0];

	} while ($txt_width <= $txt_max_width);

	return ($img_width - $txt_width) / 2;
}

function resizeUnderscore( $text, $size = 0) {
	global $font;

	if($size == 2) {
		$img_width = 128;
		$img_height = 128;
		$font_size = 55;
	} else {
		$img_width = 64;
		$img_height = 64;
		$font_size = 25;
	}
	$tmpWidth = 0.32;

	$txt_max_width = intval($tmpWidth * $img_width);

	do {

	    $font_size++;
	    $p = imagettfbbox($font_size, 0, $font, $text);
	    $txt_width = $p[2] - $p[0];

	} while ($txt_width <= $txt_max_width);

	$tmp = ($img_width - $txt_width);
	return ($tmp / 2);
}



main();
?>