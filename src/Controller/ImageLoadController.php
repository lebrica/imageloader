<?php


namespace App\Controller;


use App\Service\ImageLoader;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ImageLoadController extends AbstractController
{
    public function load(Request $request, ImageLoader $imageLoader): Response
    {
        $url = $request->request->get('url');
        $width = (int)$request->request->get('width');
        $height = (int)$request->request->get('height');

        $font = 'font/noto-sans-italic.ttf';
        $text = 'text';
        $rgb = [255,0,155];

        $images = [];

        if ($this->validUrl($url) === true) {
            $html = file_get_contents($url);
            preg_match_all('/<img.*?src=["\'](.*?)["\'].*?>/i', $html, $imagesUrl, PREG_SET_ORDER);

            $url = parse_url($url);
            $ext = '';

            foreach ($imagesUrl as $image) {

                $urlImg = $image[1];

                if (str_contains($urlImg, 'data:image/')) {
                    continue;
                }

                if (substr($urlImg, 0, 2) == '//') {
                    $urlImg = $url['scheme'] .':' . $urlImg;
                }

                if  (array_key_exists('extension', pathinfo($urlImg))) {
                    $ext = pathinfo($urlImg)['extension'];
                }

                if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
                    $img = $this->validImg($urlImg, $width, $height);
                    if ($img !== false) {
                        $images[] = $imageLoader->save($img, $text, $rgb, $font);
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
            }
        } else {
            return new JsonResponse('error-link');
        }
        return new JsonResponse($images);
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

    private function validImg($url, $width, $height): bool|array
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->get($url);
            $img = (string)$response->getBody();
            $imgData = getimagesizefromstring($img);
            if ($imgData !== false) {
                if ($imgData[0] < $width || $imgData[1] < $height) {
                    return false;
                }
               return ['body' => $img,'data' => $imgData];
            }
        } catch (RequestException | ConnectException) {
            return false;
        }
    }

    private function validUrl($url)
    {
        $client = new \GuzzleHttp\Client();
        try {
            $client->head($url);
            return true;
        } catch (RequestException | ConnectException) {
            return false;
        }
    }
}