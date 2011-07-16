<?php

/**
* $Id: ScaleImage.php,v 1.2 2002/05/24 20:38:18 dennis Exp $
* @author Dennis Iversen <dennisbech@yahoo.dk>
* @version 0.1
*/

class PHP_ScaleImage
{

    /**
    * placeholder for image being manipulated
    * @public  string
    */
    public $image;

    /**
    * quality of the scaled image (only applying to jpg)
    * @public  int
    */
    public $quality=100;

    /**
    * placeholder for imageType, eg. 'jpg' or 'png'
    * public $imageType;
    */
    public $imageType;

  
    /**
    * get vaious info about image. If imageType is empty, the class will try
    * to detect the 'imageType'. Currently autodetection of 'imageType' works with
    * png, gif, jpeg. Set imageType if image is eg. GD or WBMP.
    *
    * @constructor
    * @param    string  orginal image to scale (url or file)
    * @param    string  imageType (eg. GD, WBMP)
    */
    function __construct($image='', $imageType=''){
        if (isset($image)){
            $this->setImage($image, $imageType);
        }
    }

    /**
    * open info and set info
    *
    * @param    string  (image or url)
    * @access   public
    */
    function setImage($image, $imageType=''){

        $this->setImageInfo($image);
        if ($this->imageType=='unknown'){
            $this->imageType=$imageType;
            if ( empty($this->imageType) || $this->imageType == 'unknown'){
                die("Specify imageType to scale from");
            }
        }
        if ($this->imageType=='gif'){
            $this->image=imagecreatefromgif($image);
        } else if ($this->imageType=='jpg' || $this->imageType=='jpeg'){
            $this->image=imagecreatefromjpeg($image);
        } else if ($this->imageType=='png'){
            $this->image=imagecreatefrompng($image);
        } else if ($this->imageType=='gd'){
            $this->image=imagecreatefromgd($image);
        } else {
            die("Unsupported source image type: $imageType");
        }
    }


    /**
    * find image size
    *
    * @param    string          file path
    * @return   array   image info
    * @access   private
    */
    function setImageInfo($image){
        $this->info=getimagesize($image, $this->info);
        if ($this->info[2]==1){
            $this->imageType='gif';
        } else if ($this->info[2]==2){
            $this->imageType='jpg';
        } else if ($this->info[2]==3){
            $this->imageType='png';
        } else {
            $this->imageType='unknown';
        }
    }

    /**
    * scale according to a Maximum height
    *
    * @param    int         maxWidth (maximum width)
    * @param    string      distImageType (scale to this imageType (jpg/png)
    * @param    string      save image in this file (if empty output to
    *                       browser)
    * @access   public
    */
    function scaleMaxHeight ($maxHeight, $filename='', $distImageType=''){
        if (empty($distImageType)){
            $distImageType=$this->imageType;
        }
        if ($this->info[0] <> $this->info[1]){
            $x = $maxHeight;
            $div= $this->info[0] / $maxHeight;
            $y = (int) $this->info[1] / $div;
        } else {
            $x=$y=$maxHeight;
        }
        $this->scale($x, $y, $filename, $distImageType);
    }

    /**
    * scale according to a Maximum width
    *
    * @param    int         maxWidth (maximum width)
    * @param    string      imageType (scale to this imageType (jpg/png)
    * @param    string      save image in this file (if empty output to
    *                       browser)
    * @access   public
    */
    function scaleMaxWidth($maxWidth, $filename='', $distImageType=''){
        if (empty($distImageType)){
            $distImageType=$this->imageType;
        }
        if ($this->info[0] <> $this->info[1]){
            $y = $maxWidth;
            $div= $this->info[1] / $maxWidth;
            $x = $this->info[0] / $div;
        } else {
            $x=$y=$maxWidth;
        }
        $this->scale($x, $y, $filename, $distImageType);
    }

    /**
    * scale according to x and y cordinates
    *
    * @param    int         x Width
    * @param    int         y Height
    * @param    string      imageType (scale to this imageType (jpg/png)
    * @param    string      save image in this file (if empty output to
    *                       browser)
    * @access   public
    */
    function scaleXY($x, $y, $filename='', $dst_x = '', $dst_y = '', $compressionWidth, $compressionHeight, $border=false){
        $this->scale($x, $y, $filename, $this->imageType, $dst_x, $dst_y,  $compressionWidth, $compressionHeight, $border);
    }

//    function scaleXY($x, $y, $filename='', $distImageType=''){
//        $this->scale($x, $y, $filename, $distImageType);
//    }

    /**
    * scale image so the largest of x or y has gets a max of q
    *
    * @param    int         max Width or Height
    * @param    string      imageType (scale to this imageType (jpg/png)
    * @param    string      save image in this file (if empty output to
    *                       browser)
    * @access   public
    */
    function scaleXorY($max, $filename='', $distImageType=''){
        if ($this->info[0] < $this->info[1]){
            $this->scaleMaxWidth($max, $filename, $distImageType);
        } else {
            $this->scaleMaxHeight($max, $filename, $distImageType);
        }
    }
    /**
    * scale according to a percentage, eg 50.
    *
    * @param    int         percentage (percentage)
    * @param    string      imageType (scale to this imageType (eg. jpg/png)
    * @param    string      save image in this file (if empty output to
    *                       browser)
    * @access   public
    */
    function scalePercentage($percentage, $filename='', $distImageType=''){
        if (empty($distImageType)){
            $distImageType=$this->imageType;
        }
        $percentage=$percentage/100;
        $x=$percentage * $this->info[0];
        $y=$percentage * $this->info[1];
        $this->scale($x, $y, $filename, $distImageType);
    }


    function GreateImage($distImage, $filename='', $distImageType='')
    {
    	if ($distImageType=='gif')
        {
            if (empty($filename))
            {
                header("Content-Type: image/gif");
                $res=@imagejpeg($distImage, '', $this->quality);
            }
            else
                imagegif($distImage, $filename, $this->quality);
            
        } 
        else if ($distImageType=='jpg' || $distImageType=='jpeg')
        {
            if (empty($filename))
            {
                header("Content-Type: image/jpeg");
                imagejpeg($distImage, '', $this->quality);
            }
            else
            	imagejpeg($distImage, $filename, $this->quality);
            
        } 
        else if ($distImageType=='png')
        {
        	if (empty($filename))
            {
                header("Content-Type: image/png");
                imagepng($distImage, '', $this->quality);
            }
            else 
                imagepng($distImage, $filename, $this->quality);
        }
        else if ($distImageType=='gd')
        {
            if (empty($filename))
            {
                header("Content-Type: image/gd");
                imagegd($distImage, '', $this->quality);
            }
            else 
                imagegd($distImage, $filename, $this->quality);
        } 
        else if ($distImageType=='wbmp')
        {
            if (empty($filename))
            {
                header("Content-Type: image/wbmp");
                imagewbmp($distImage, '', $this->quality);
            }
            else
                imagewbmp($distImage, $filename, $this->quality);
        } 
        else
        {
            trigger_error("Could'nt transform image!");
        }
    }
    
    /**
    * scale the image
    *
    * @param    $x          width
    * @param    $y          height
    * @param    $imageType  imageType (type of image)
    * @param    string      filename (file to put image to)
    * @access   private
    */
    function scale($x, $y, $filename='', $distImageType='', $dst_x = '', $dst_y = '',  $compressionWidth = 0, $compressionHeight = 0, $border = false)
    {
    	 $distImage = imagecreatetruecolor($x, $y);
    	 
    	 if (strlen($dst_x) == 0 && strlen($dst_y) == 0)
    	    $this->copyResampled($distImage, $this->image, $x, $y);
    	 else 
    	 {
    	 	$image = imagecreate($x, $y);
    		imagecolorallocate($image, 255, 255, 255); 
    	
    		imagecopy($distImage, $image, 0, 0, 0, 0, $x, $y); // создаем белый фон
    	
    	 	$this->copyResampledXY($distImage, $this->image, $dst_x, $dst_y, $compressionWidth, $compressionHeight);
    	 }
        
    	 // рисуем рамку
    	 if ($border)	// draw the polygon
			imagerectangle($distImage, 0, 0, $x - 1, $y - 1, imagecolorallocate($distImage, 173, 173, 173));

        $this->GreateImage($distImage, $filename, $distImageType);
    }

    /**
    * resample the image
    *
    * @param    resource    distImage (destination image)
    * @param    resource    image (sourceImage)
    * @param    int
    * @param    int
    * @access   private
    */
    function copyResampled(&$distImage, $image, $x, $y){
        imagecopyresampled(
            $distImage,
            $image,
            0, 0, 0, 0,
            $x,
            $y,
            $this->info[0],
            $this->info[1]
        );
        return '';
    }
    
    function copyResampledXY(&$distImage, $image, $dst_x, $dst_y, $x, $y)
    {
    	imagecopyresampled(
            $distImage,
            $image,
            $dst_x, $dst_y, 0, 0,
            $x,
            $y,
            $this->info[0],
            $this->info[1]
        );
        return '';
    }
}


?>