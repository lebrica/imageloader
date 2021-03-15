<?php


namespace App\Service;


class ImageLoader
{
    public function save($img)
    {
        $linkImg = 'images/image' . rand().'.jpg';

        imagejpeg($this->resize($img), $linkImg, 100);
    }

    private function resize($img, $width = 200, $height = 200)
    {
        $imgInfo = getimagesize($img);
        $baseWidth = $imgInfo['0'];
        $baseHeight = $imgInfo['1'];
        $type = $imgInfo['2'];

        match ($type) {
            2 => $image = imageCreateFromJpeg($img),
            3 => $image = imageCreateFromPng($img),
        };

        $tmp = imageCreateTrueColor($width, $height);

        imageCopyResampled($tmp,$image,0,0,0,0,$width,$height,$baseWidth,$baseHeight);

        return $tmp;
    }

    private function addText()
    {

    }
}