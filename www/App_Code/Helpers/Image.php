<?
class Helpers_Image
{
	static function GetImage($image, $width = 0, $height = 0, $title = "", $crop = false)
	{
		$imgPath = DAL_ImagesDb::GetImagePath($image);
		$imgSrc = $imgPath;
		$imgAttr = "";
		
		if ($width > 0)
		{
			$imgSrc .= '&width='.$width;
			$imgAttr .= ' width="'.$width.'"';
		}
		
		if ($height > 0)
		{
			$imgSrc .= '&height='.$height;
			$imgAttr .= ' height="'.$height.'"';
		}
		
		if ($crop == true)
			$imgSrc .= '&crop=1';
			
/*		return '<a href="/image.php?id='.$image['ImageId'].'" title="Увеличить изображение"><img src="'.$imgPath.'" alt="'.$title.'" title="'.$title.'" /></a>';*/
		$result = '<a href="'.$imgPath.'" title="Увеличить изображение" target="_blank" class="imageContainerLink"><img hspace="10" src="'.$imgSrc.'" alt="'.$title.'" title="'.$title.'" id="imageContainerImg"';
		$result .= $imgAttr;
		$result .= ' /></a>';
		
		return $result;
	}
}
?>