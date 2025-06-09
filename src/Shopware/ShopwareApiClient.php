<?php

namespace App\Shopware;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use App\Csv\FileProcessorInterface;

class ShopwareApiClient
{
    private string $apiUrl;
    private Client $client;
    private string $accessToken;
    private array $manufacturerList = [];
    private float $timeout;
    private string $clientId;
    private string $clientSecret;

    public function __construct(string $apiUrl, string $clientId, string $clientSecret)
    {
        $this->apiUrl = $apiUrl;
        $this->client = new Client(['base_uri' => $this->apiUrl]);
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->authenticate($clientId, $clientSecret);
    }

    private function authenticate(string $clientId, string $clientSecret): void
    {
        try {
            $response = $this->client->post('/api/oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->accessToken = $data['access_token'];
            $this->timeout = microtime(true);
        } catch (RequestException $e) {
            throw new \Exception('Failed to authenticate with Shopware API: ' . $e->getMessage());
        }
    }

    public function checkTimeout(): void
    {
        if (microtime(true) - $this->timeout > 400) {
            $this->authenticate($this->clientId, $this->clientSecret);
        }
    }

    public function importProduct(array $productData): ?string
    {
        unset($productData['media']);
        try {
            $response = $this->client->post('/api/product?_response=basic', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'body' => json_encode($productData, JSON_UNESCAPED_UNICODE ),
            ]);

            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['data']['id'] ?? null; // Gib die productId zurück
            } elseif ($response->getStatusCode() !== 200 && $response->getStatusCode() !== 204) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                d($responseData);
                throw new \Exception('Failed to import product, HTTP status: ' . $response->getStatusCode());
            }
    
            return null;
        } catch (RequestException $e) {
            d('Error importing product: ' . $e->getMessage(), json_encode($productData, JSON_UNESCAPED_UNICODE ));
            return false;
        }
    }

    public function updateProduct(string $productId, array $productData): bool
    {
        if (isset($productData['media'])) {
            unset($productData['media']);
        }
        try {
            $this->client->patch('/api/product/' . $productId, [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($productData, JSON_UNESCAPED_UNICODE ),
            ]);
            return true;
        } catch (RequestException $e) {
            d('Error updating product: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct(string $productId): bool
    {
        try {
            $this->client->delete('/api/product/' . $productId, [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);
            return true;
        } catch (RequestException $e) {
            d('Error updating product: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteProductArray(array $productArray): bool
    {
        try {
            $this->client->delete('/api/_action/sync/', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode( [
                    'delete-product' => [
                        [
                            'entity'  => 'product',
                            'action' => 'delete',
                            'payload' => $productArray,
                        ]
                    ]
                ], JSON_UNESCAPED_UNICODE ),
            ]);
            return true;
        } catch (RequestException $e) {
            d('Error updating product: ' . $e->getMessage());
            return false;
        }
    }

    public function productExists(string $productNumber): string|bool
    {
        try {
            $response = $this->client->post('/api/search/product', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'body' => json_encode( [
                    'filter' => [
                        [
                            'type'  => 'equals',
                            'field' => 'productNumber',
                            'value' => $productNumber,
                        ]
                    ]
                ], JSON_UNESCAPED_UNICODE ),
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            // Wenn 'total' größer als 0 ist, wurde das Produkt gefunden
            if (isset($responseData['meta']['total']) && $responseData['meta']['total'] > 0) {
                // Gib die ID des ersten Produkts zurück (wenn gefunden)
                return $responseData['data'][0]['id'];
            }

            return false; // Produkt nicht gefunden
        } catch (RequestException $e) {
            d('Error checking product existence: ' . $e->getMessage());
            return false;
        }
    }

    public function productHasMedia(string $productId): bool
    {
        try {
            // API-URL zum Abrufen des Produkts
            $response = $this->client->get('/api/product/' . $productId . '/media', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    // 'Content-Type'  => 'application/json',
                ],
            ]);

            $productData = json_decode($response->getBody()->getContents(), true);

            // Prüfen, ob das Produkt Medien zugeordnet hat
            if (isset($productData['data']) && count($productData['data']) > 0) {
                return true; // Produkt hat mindestens ein Media-Objekt zugeordnet
            }

            return false; // Produkt hat kein Media-Objekt zugeordnet
        } catch (RequestException $e) {
            d('Error checking product media: ' . $e->getMessage());
            return false;
        }
    }

    public function mediaExistsByUrl(string $mediaUrl): mixed
    {
        try {
            // API-URL zum Durchsuchen von Media-Einträgen
            $response = $this->client->post('/api/search/media', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                // Suche nach einem Media-Eintrag, der den gleichen Dateinamen wie die URL hat
                'body' => json_encode([
                    'filter' => [
                        [
                            'type'  => 'equals',
                            'field' => 'url',
                            'value' => $mediaUrl,
                        ]
                    ]
                ], JSON_UNESCAPED_UNICODE ),
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            // Wenn 'total' größer als 0 ist, wurde das Media-Objekt gefunden
            if (isset($responseData['total']) && $responseData['total'] > 0) {
                // Gib die ID des ersten Media-Objekts zurück
                return $responseData['data'][0]['id'];
            }

            return false; // Media-Objekt nicht gefunden
        } catch (RequestException $e) {
            throw new \Exception('Error checking media existence: ' . $e->getMessage());
        }
    }

    public function uploadMedia(string $mediaId, string $mediaUrl)
    {
        $fileData = file_get_contents($mediaUrl);
        $fileExtension = pathinfo($mediaUrl, PATHINFO_EXTENSION);
        try {
            $headers = [
                'Accept' => 'application/json, application/vnd.api+json, application/json',
                'Content-Type' => 'application/octet-stream',
                'Authorization' => 'Bearer ' . $this->accessToken,
            ];
            $body = $fileData;

            $request = new Request('POST', '/api/_action/media/' . $mediaId . '/upload?extension=' . $fileExtension . '&fileName=' . pathinfo($mediaUrl, PATHINFO_FILENAME), $headers, $body);
            $response = $this->client->sendAsync($request)->wait();

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
                return true;
            } else {
                d('Fehler beim Erstellen der Media-Entity: ' . $response->getStatusCode());
                return false;
            }
        } catch (RequestException $e) {
            d('Error uploading media: ' . $e->getMessage(), $mediaUrl);
            return false;
        }
    }

    public function productSetCover(string $productId, string $mediaId): bool
    {
        try {
            // API-URL zum Aktualisieren des Produkts
            $response = $this->client->patch('/api/product/' . $productId, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Content-Type'  => 'application/json',
                ],
                // Produkt-Update mit Medien-ID
                'body' => json_encode([
                    'coverId' => $mediaId
                ], JSON_UNESCAPED_UNICODE)
            ]);

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
                return true;
            } else {
                throw new \Exception('Fehler beim Erstellen der Media-Entity: ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            echo "Fehler beim Erstellen der Media-Entity: " . $e->getMessage();
            return false;
        }
    }

    public function createMedia(string $mediaUrl): false|string
    {
        try {
            // API-URL zum Aktualisieren des Produkts
            $response = $this->client->post('/api/media?_response=basic', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Content-Type'  => 'application/json',
                ],
                // Produkt-Update mit Medien-ID
                'body' => json_encode([
                    'mediaFolderId' => FileProcessorInterface::MEDIA_FOLDER,
                ], JSON_UNESCAPED_UNICODE)
            ]);

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['data']['id'] ?? false; // Gib die productId zurück
            } else {
                throw new \Exception('Fehler beim Erstellen der Media-Entity: ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            echo "Fehler beim Erstellen der Media-Entity: " . $e->getMessage();
            return false;
        }
    }

    public function addMediaToProduct(string $productId, string $mediaId): false|string
    {
        try {
            // API-URL zum Aktualisieren des Produkts
            $response = $this->client->post('/api/product-media?_response=detail', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Content-Type'  => 'application/json',
                ],
                // Produkt-Update mit Medien-ID
                'body' => json_encode([
                    'productId' => $productId,
                    'mediaId' => $mediaId,
                    'position' => 0

                ], JSON_UNESCAPED_UNICODE)
            ]);

            if ($response->getStatusCode() === 200 || $response->getStatusCode() === 204) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['data']['id'] ?? false; // Gib die productId zurück
            } else {
                throw new \Exception('Fehler beim Erstellen der Media-Entity: ' . $response->getStatusCode());
            }
        } catch (RequestException $e) {
            echo "Fehler beim Erstellen der Media-Entity: " . $e->getMessage();
            return false;
        }
    }

    public function getManufacturerList(bool $force = false): array
    {
        if (empty($this->manufacturerList) || $force == true) {
            $this->manufacturerList = $this->getManufacturerFromApi();
        }

        return $this->manufacturerList;
    }

    public function findManufacturer(string $manufacturer): string|false
    {
        $key = array_search ($manufacturer, $this->manufacturerList);
        if ($key !== false) {
            return (string) $key;
            // return (string) $this->manufacturerList[$key];
        }

        return false;
    }

    private function getManufacturerFromApi(): array
    {
        $result = [];
        try {
            $response = $this->client->get('/api/product-manufacturer', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $productData = json_decode($response->getBody()->getContents(), true);

            // Prüfen, ob das Produkt Medien zugeordnet hat
            if (isset($productData['data']) && count($productData['data']) > 0) {
                foreach ($productData['data'] as $item) {
                    $result[$item['id']] = $item['attributes']['name'];
                }
            }

            return $result;
        } catch (RequestException $e) {
            d('Error checking product media: ' . $e->getMessage());
            return [];
        }
    }

    public function createManufacturer(string $manufacturer): string|false
    {
        try {
            $response = $this->client->post('/api/product-manufacturer?_response=basic', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'name' => $manufacturer,
                ], JSON_UNESCAPED_UNICODE)
            ]);

            $this->getManufacturerList(true);

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['data']['id'] ?? false; // Gib die productId zurück
            }

            return false;
        } catch (RequestException $e) {
            d('Error checking product media: ' . $e->getMessage());
            return false;
        }
    }

    public function findPropertyOptionId(string $propertyGroupId, string $optionName): ?string
    {
        $params = [
            'filter' => [
                'groupId' => $propertyGroupId,
                'name' => $optionName,
            ]
        ];

        try {
            $response = $this->client->get('/api/property-group-option', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'query' => $params
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!empty($responseData['data'])) {
                return $responseData['data'][0]['id']; // Вернуть id найденной опции
            }

            return null; // Если опция не найдена
        } catch (RequestException $e) {
            d('Error find property: ' . $e->getMessage(), $params);
            return false;
        }
    }

    public function createPropertyOption(string $propertyGroupId, string $optionName): string
    {
        d('createPropertyOption', $propertyGroupId, $optionName);
        try {
            $response = $this->client->post('/api/property-group-option?_response=basic', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'groupId' => $propertyGroupId,
                    'name' => $optionName
                ], JSON_UNESCAPED_UNICODE)
            ]);

            if ($response->getStatusCode() == 200 || $response->getStatusCode() == 204) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                return $responseData['data']['id'] ?? false; // Gib die productId zurück
            }

            return false;
        } catch (RequestException $e) {
            d('Error checking property option: ' . $e->getMessage());
            return false;
        }
    }

    public function findMediaByName(string $name): ?string
    {
        $params = [
            'filter' => [
                [
                    'type' => "contains",
                    'field' => "fileName",
                    'value' => $name,
                ]
            ]
        ];

        try {
            $response = $this->client->post('/api/search/media', [
                'headers' => [
                    'Accept' => 'application/vnd.api+json, application/json',
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($params, JSON_UNESCAPED_UNICODE)
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!empty($responseData['data'])) {
                return $responseData['data'][0]['id']; // Вернуть id найденной опции
            }

            return null; // Если опция не найдена
        } catch (RequestException $e) {
            d('Error find media: ' . $e->getMessage(), $params);
            return null;
        }
    }
}
