<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\ImageLoader;


class ImageLoadController extends AbstractController
{
    public function homepage(ImageLoader $imageLoader): Response
    {
        //$image = 'https://gurutest.ru/uploads/ckeditor/2019/02/05/vxyfpx.jpg';
        $image = 'https://upload.wikimedia.org/wikipedia/ru/4/4f/Virtus.proLogo.png';

        $font = 'font/noto-sans-italic.ttf';
        $text = 'text';
        $rgb = [255,255,255];
        $rgb = [255,0,155];





        $imageLoader->save($image,$text,$rgb,$font);

        return $this->render('home.html.twig', array('img' => $image));
    }
}