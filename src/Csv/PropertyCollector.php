<?php

namespace App\Csv;

use App\Shopware\ShopwareApiClient;

class PropertyCollector
{
    public const PROPERTIES_IDS = [
        'author' => '01925bec227b77728717107ebedc2169',
        'illustrator' => '01925bf62ee47893a226bf9644190671',
        'series' => '01925bf28eb671278361f48de85df1e7',
        'age' => '01925bf529cb70928695a59013a0315a',
        'releaseYear' => '01925bf574147a11a14c39a88f4a280a',
        'pageCount' => '01925bf5a71e7a89afc45e5584b564c4',
        'publishing' => '01925d746af77858b8e15f753ab558f9',
        'binding' => '019377958305744f9d3065572fab41d4',
        'format' => '01937795c5fa71c19613f5108229d773',
    ];

    public function __construct(
        protected ShopwareApiClient $shopwareClient,
        private array $propertiesArray = []
    ) {

        foreach (self::PROPERTIES_IDS as $propertyName => $groupId) {
            $this->propertiesArray[$propertyName] = [
                'propertyGroupId' => $groupId,
                'propertyName' => $propertyName,
                'options' => []
            ];
        }
    }

    public function collectProperties(array $productData): void
    {
        foreach ($productData['properties'] as $propertyName => $propertyValue) {
            if ($this->isValidProperty($propertyName) && $propertyValue) {
                $propertyGroupId = $this->propertiesArray[$propertyName]['propertyGroupId'];

                // Проверяем, существует ли опция
                $optionId = $this->shopwareClient->findPropertyOptionId($propertyGroupId, $propertyValue);

                if ($optionId === null) {
                    // Если опция не найдена, создаём её
                    $optionId = $this->shopwareClient->createPropertyOption($propertyGroupId, $propertyValue);
                }

                // Добавляем в массив propertiesArray с найденным/созданным optionId
                $this->propertiesArray[$propertyName]['options'][] = [
                    'id' => $optionId,
                    'name' => $propertyValue
                ];
            }
        }
    }

    public function getPropertiesArray(): array
    {
        return $this->propertiesArray;
    }

    private function isValidProperty(string $propertyName): bool
    {
        return isset(self::PROPERTIES_IDS[$propertyName]);
    }
}
