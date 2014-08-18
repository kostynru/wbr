<?php
/**
 * Class for  Generator Image Color Palette.  
 * This file is class for return  mamimum used colors in image, format return in HTML or RGB format. 
 *
 * PHP versions 4 and 5
 * Licensed under The GPL License
 *
 * Developed By 	Kalpeh Gamit
 * E-mail 			Kalpa.programmer@gmail.com
 *
 * @filesource
 * @copyright     Copyright 2009, InScripter Inc. (http://www.inscripter.com)
 * @link          http://www.inscripter.com
 * @package       PHP
 * @version       GetImageColor-1.0.0
 * @license       http://www.opensource.org/licenses/gpl-2.0.php The GPL License
 */
 
/**
 * Применение:
 *	$img = new GeneratorImageColorPalette();
 *	$colors = $img->getImageColor($pic , $colors_num, $step);
 *	
 * 	$pic = 'путь к файлу'; (string)
 *	$colors_num = 'количество возвращаемых цветов'; (int)
 *	$step = 'шаг пикселей по вертикали и горизонтали'; (int)
 *
 *	$colors -> возращается ассоциативный массив ( [цвет в hex]=>[количество вхождений] )
 */

// start of class : Generator Image Color Palette 
class GeneratorImageColorPalette 
{
	public $error = 1; 
	//default 0x33;
	//погрешность определения цвета
	
	// get image color in RGB format function 
	function getImageColor($imageFile_URL, $numColors, $image_granularity = 5)
	{
   		$image_granularity = max(1, abs((int)$image_granularity));
   		$colors = array();
   		//find image size
   		$size = @getimagesize($imageFile_URL);
   		if($size === false)
   		{
      		user_error("Unable to get image size data");
      		return false;
   		}
   		// open image
   		$img = @imagecreatefromjpeg($imageFile_URL);
   		if(!$img)
   		{
   	  		user_error("Unable to open image file");
   		   return false;
   		}
   		
   		// fetch color in RGB format
   		for($x = 0; $x < $size[0]; $x += $image_granularity)
   		{
      		for($y = 0; $y < $size[1]; $y += $image_granularity)
      		{
         		$thisColor = imagecolorat($img, $x, $y);
         		$rgb = imagecolorsforindex($img, $thisColor);

        		$red = round(round(($rgb['red'] / $this->error)) * $this->error);
         		$green = round(round(($rgb['green'] / $this->error)) * $this->error);
         		$blue = round(round(($rgb['blue'] / $this->error)) * $this->error);
				
				$red = ($red>255) ? 255 : $red;
				$green = ($green>255) ? 255 : $green;
				$blue = ($blue>255) ? 255 : $blue;
				
				$red = dechex($red);
				$green = dechex($green);
				$blue = dechex($blue);
				
				$red = (strlen($red) == 1) ? '0'.$red : $red;
				$green = (strlen($green) == 1) ? '0'.$green : $green;
				$blue = (strlen($blue) == 1) ? '0'.$blue : $blue;
				
				
         		//$thisRGB = sprintf('%02X%02X%02X', $red, $green, $blue);
				//$thisRGB = dechex($red).dechex($green).dechex($blue);
				$thisRGB = $red.$green.$blue;
				
         		if(array_key_exists($thisRGB, $colors))
         		{
           			 $colors[$thisRGB]++;
         		}
         		else
         		{
           			 $colors[$thisRGB] = 1;
         		}
      		}
   		}
   		arsort($colors);
   		// returns maximum used color of image format like #C0C0C0.
   		return array_slice(($colors), 0, $numColors,true);
	}

	// html color to convert in RGB format color like R(255) G(255) B(255)  
	function getHtml2Rgb($str_color)
	{
    	if ($str_color[0] == '#')
        	$str_color = substr($str_color, 1);

  	  	if (strlen($str_color) == 6)
        	list($r, $g, $b) = array($str_color[0].$str_color[1],
                                 $str_color[2].$str_color[3],
                                 $str_color[4].$str_color[5]);
    	elseif (strlen($str_color) == 3)
        	list($r, $g, $b) = array($str_color[0].$str_color[0], $str_color[1].$str_color[1], $str_color[2].$str_color[2]);
    	else
        	return false;

    	$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
    	$arr_rgb = array($r, $g, $b);
		// Return colors format liek R(255) G(255) B(255)  
    	return $arr_rgb;
	}

// end of class here : Generator Image Color Palette  
}