<?php

namespace App\Csv;

use GuzzleHttp\Client;
use DiDom\Document;

class ImageDownloader
{
    private $client;
    private $imgDir;
    private $logger;
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
        $isbnSmall = str_replace("-", "", $isbn);

        if(isset($productData['media'])) {
            $url = $productData['media'];
            $this->logger->info("Media existiert: $url als $isbn");

            if (filter_var($url, FILTER_VALIDATE_URL) !== false) {
                if ($fileName = $this->imageExists($isbn)) {
                    return $fileName;
                }

                if ($fileName = $this->imageExists($isbnSmall)) {
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
                $this->logger->info("Robins: $url als $isbn");
                $result = $this->findByRobins($url, $isbn);

                if (!empty($result)) {
                    return $result;
                }
            }

            if (str_contains($url, "mann-ivanov-ferber")) {
                $this->logger->info("MIF: $url als $isbn");
                $result = $this->findByMif($url, $isbn);

                if (!empty($result)) {
                    return $result;
                }
            }
        }

        $this->logger->info("Mnogoknig: $isbnSmall");
        $result = $this->findByMnogoknig($isbnSmall);
        
        if (!empty($result)) {
            return $result;
        }
        
        // $result = $this->findByJanzen($isbn);

        return $result;
    }

    private function saveImage(string $url, string $name): ?string
    {
        try {
            if ($this->logger) {
                $this->logger->info("Versuche Bild zu speichern: $url als $name");
            }
            // $response = $this->client->request('GET', $url, ['http_errors' => false, 'stream' => true]);
            // $contentType = $response->getHeaderLine('Content-Type');

            $contentType = $this->getContentType($url);

            if (str_starts_with($contentType, 'image/')) {
                $extension = explode('/', $contentType)[1];
                if ($extension == 'jpeg') {
                    $extension = 'jpg';
                }

                $savePath = rtrim($this->imgDir, '/') . '/' . $name . '.' . $extension;
                $this->client->get($url, [
                    'sink' => $savePath,
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' .
                                        '(KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                        'Referer' => 'https://google.com',
                        'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                    ],
                ]);
                if ($this->logger) {
                    $this->logger->info("Bild gespeichert: $savePath");
                }
                return $savePath;
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if ($this->logger) {
                $this->logger->error("Fehler beim HEAD-Request: " . $e->getMessage());
            }
            return null;
        }  catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error("Fehler beim HEAD-Request: " . $e->getMessage());
            }
            return null;
        }

        error_log("ER-03 Kein Bild");
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

    private function findByMnogoknig(string $isbn): ?string
    {
        $url = "https://mnogoknig.de/de/search?query=" . $isbn;
        try {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // 2. Prüfen, ob Zugriff erfolgreich war
            if ($httpCode !== 200 || !$html) {
                error_log("Fehler beim Laden von $url: HTTP $httpCode");
                return null;
            }

            $document = new Document($html);
            $error = [];
            $items = $document->find('#product-content > div');

            if (!$items || !count($items) || count($error) > 0) {
                error_log("ER-01. Bild nicht gefunden");
                return null;
            }

            $image = $items[0]->find('.flex.relative img');

            if (!$image || !count($image)) {
                error_log(message: "ER-02. Bild wurde nicht gefunden");
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

    private function findByMif(string $url, string $isbn): ?string
    {
        if (empty($url)) {
            return null;
        }

        try {
            $document = new Document($url, true);
            
            $h1 = $document->first('h1');
            if (!$h1) {
                return null;
            }

            // Поднимаемся на 2 уровня вверх
            $parent = $h1->parent();
            if (!$parent) return null;
            
            $grandParent = $parent->parent();
            if (!$grandParent) return null;

            // Ищем <img>, где src содержит "1.50x-thumb"
            $images = $grandParent->find('img');
            
            foreach ($images as $img) {
                $src = $img->getAttribute('src');
                if (strpos($src, "1.50x-thumb") !== false) {
                    $img = $this->getBaseUrl($url, $src);
                    return $this->saveImage($img, $isbn);
                }
            }

            return null;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }  catch (\Exception $e) {
            error_log("Fehler beim HEAD-Request: " . $e->getMessage());
            return null;
        }
    }

    private function getContentType(string $url): ?string {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD-Request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
        // Browser-ähnlicher Header
        curl_setopt($ch, CURLOPT_USERAGENT, 
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 ' . 
            '(KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36');
        curl_setopt($ch, CURLOPT_REFERER, 'https://google.com');
    
        curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
        if ($httpCode === 200) {
            return $contentType;
        }
    
        return null;
    }

    public function setLogger($logger): void
    {
        $this->logger = $logger;
    }
}
