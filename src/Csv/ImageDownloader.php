<?php

namespace App\Csv;

use GuzzleHttp\Client;
use DiDom\Document;

class ImageDownloader
{
    private $client;
    private $imgDir;
    public function __construct() {
        $this->imgDir = dirname(__DIR__, 2) . '/book_images';
        $this->client = new Client([
            'verify' => false,
            'timeout' => 10
        ]);
    }
    public function downloadImage(array $productData): ?string
    {
        $isbn = $productData['ean'];

        if(isset($productData['media'])) {
            $url = $productData['media'];

            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                if ($fileName = $this->imageExists($isbn)) {
                    return $fileName;
                }

                try {
                    $response = $this->client->request('GET', $url, ['http_errors' => false, 'stream' => true]);
                    $contentType = $response->getHeaderLine('Content-Type');

                    // d($contentType);

                    // $headers = get_headers($url, 1);

                    if (str_starts_with($contentType, 'image/')) {
                        $extension = explode('/', $contentType)[1];
                        if ($extension == 'jpeg') {
                            $extension = 'jpg';
                        }

                        return $this->saveImage($url, $isbn);
                    }
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    error_log("Fehler beim HEAD-Request: " . $e->getMessage());
                }  catch (\Exception $e) {
                    error_log("Fehler beim HEAD-Request: " . $e->getMessage());
                }
            }

            if (isset($productData['manufacturer']) && $productData['manufacturer'] == 'РОБИНС') {
                $result = $this->findByRobins($url, $isbn);

                if (!empty($result)) {
                    return $result;
                }
            }
        }

        $result = $this->findByMurawei($isbn);
        
        if (!empty($result)) {
            return $result;
        }
        
        // $result = $this->findByJanzen($isbn);

        return $result;
    }

    private function saveImage(string $url, string $name): ?string
    {
        try {
            $response = $this->client->request('GET', $url, ['http_errors' => false, 'stream' => true]);
            $contentType = $response->getHeaderLine('Content-Type');

            if (str_starts_with($contentType, 'image/')) {
                $extension = explode('/', $contentType)[1];
                if ($extension == 'jpeg') {
                    $extension = 'jpg';
                }

                $savePath = rtrim($this->imgDir, '/') . '/' . $name . '.' . $extension;
                $this->client->get($url, ['sink' => $savePath]);
                return $savePath;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }  catch (\Exception $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }

        return null;
    }

    private function getBaseUrl(string $url, $imageUrl = false): string|false
    {
        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['scheme'], $parsedUrl['host'])) {
            return false;
        }

        $link = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];

        if ($imageUrl) {
            $link = rtrim($link, '/') . '/' . ltrim($imageUrl, './');
        }

        return $link;
    }

    private function imageExists(string $fileName): ?string
    {
        $files = glob($this->imgDir . DIRECTORY_SEPARATOR . $fileName . ".*");
        foreach ($files as $file) {
            if (preg_match('/\.(jpg|jpeg|png|webp|gif|bmp)$/i', $file)) {
                return $file;
            }
        }
        return null;
    }

    private function findByMurawei(string $isbn): ?string
    {
        $url = "https://murawei.de/catalogsearch/result/?q=" . $isbn;
        try {
            $document = new Document($url, true);
            $error = $document->find('.message.notice');
            $items = $document->find('.items.product-items');

            if (!$items || !count($items) || count($error) > 0) {
                return null;
            }

            $image = $items[0]->first('.item.product')?->find('.product-image-photo');

            if (!$image || !count($image)) {
                error_log(message: "Bild wurde nicht gefunden");
                return null;
            }

            if ($image[0]->getAttribute('src')) {
                $img = $image[0]->getAttribute('src');
                return $this->saveImage($img, $isbn);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }  catch (\Exception $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }
    }

    private function findByJanzen(string $isbn): ?string
    {
        $url = "https://www.knigi-janzen.de/result_search.php?g_isbn=" . $isbn;
        try {
            $document = new Document($url, true);
            $image = $document->find("table[style*='bgcolor:#ffffff'] img");

            d($image);

            if (!$image || !count($image)) {
                error_log(message: "Bild wurde nicht gefunden");
                return null;
            }

            if ($image[0]->getAttribute('src')) {
                $img = $this->getBaseUrl($url, $image[0]->getAttribute('src'));
                $img = str_replace('small', 'big', $img);
                return $this->saveImage($img, $isbn);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }  catch (\Exception $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }
    }

    private function findByRobins(string $url, string $isbn): ?string
    {
        if (empty($url)) {
            return null;
        }

        try {
            $document = new Document($url, true);
            $slides = $document->find('#slider .slides');

            if (!$slides || !count($slides)) {
                return null;
            }

            $image = $slides[0]->first('li')?->find('a');

            if (!$image || !count($image)) {
                error_log(message: "Bild wurde nicht gefunden");
                return null;
            }

            if ($image[0]->getAttribute('href')) {
                $img = $this->getBaseUrl($url, $image[0]->getAttribute('href'));
                return $this->saveImage($img, $isbn);
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }  catch (\Exception $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }
    }
}
