<?php


namespace App\Controller;


use App\Service\ImageLoader;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ImageLoadController extends AbstractController
{
    public function load(Request $request, ImageLoader $imageLoader): Response
    {
        $image = $request->request->get('image');

        $font = 'font/noto-sans-italic.ttf';
        $text = 'text';
        $rgb = [255,0,155];

        $client = new \GuzzleHttp\Client();
        try {
            $client->head($image);

            if ($this->validImg($image) === true) {
                return new JsonResponse($imageLoader->save($image, $text, $rgb, $font));
            } else {
                return new JsonResponse('small-size');
            }
        } catch (ConnectException | ClientException $e) {
            return new JsonResponse('error-link');
        }
    }

    public function view(): Response
    {
        $path = "images/";
        $img = scandir($path);
        if ($img !== false) {
            $images = preg_grep("/\.(?:jpe?g)$/i", $img);
        }

        return $this->render('home.html.twig', array('images' => $images));
    }

    private function validImg($image)
    {
        if (getimagesize($image) !== false) {
            if (getimagesize($image)['0'] < 200 || getimagesize($image)['1'] < 200) {
                return false;
            }
            return true;
        }
    }
}