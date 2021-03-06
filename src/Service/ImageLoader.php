<?php


namespace App\Service;


class ImageLoader
{
    public function save($img, string $text,array $rgb, string $font)
    {
        $linkImg = 'images/image' .rand(). '.jpg';

        $tmp = $this->resize($img);

        $this->addText($tmp, $text, $rgb, $font);

        imagejpeg($tmp, $linkImg, 100);

        return $linkImg;
    }

    private function resize($img, $width = 200, $height = 200)
    {
        $imgInfo = $img['data'];
        $baseWidth = $imgInfo['0'];
        $baseHeight = $imgInfo['1'];

        $image = imagecreatefromstring($img['body']);

        $tmp = imageCreateTrueColor($width, $height);

        imageCopyResampled($tmp,$image,0,0,0,0,$width,$height,$baseWidth,$baseHeight);

        return $tmp;
    }


    private function addText($tmp, string $text, array $rgb, string $font)
    {
        $color = imagecolorallocate($tmp,$rgb[0],$rgb[1],$rgb[2]);

        imagettftext($tmp,34,0,60,100,$color,$font,$text );
    }
}