<?php
namespace App\Utils;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class PriceCalculator
{
    private const BASE_URL = 'https://cdn.jsdelivr.net/npm/@fawazahmed0/currency-api@latest/v1/currencies/';
    private const FALLBACK_URL = 'https://latest.currency-api.pages.dev/v1/currencies/';
    private const CURRENCY_FROM = 'rub'; // Ausgangswährung
    private const CURRENCY_TO = 'eur'; // Zielwährung
    private const MARGIN = 1.25; // 20% Marge

    private $margin = 1.25;

    private $exchangeRate = 1;

    private Client $httpClient;
    private ?array $currencyRates = null;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Konvertiert den Preis von Rubel in Euro und fügt 20% Marge hinzu
     *
     * @param float $amount Betrag in Rubel
     * @return float Betrag in Euro mit Marge
     */
    public function convertRubToEurWithMargin($amount): float
    {
        $amount = $this->parsePrice($amount);
        // $conversionRate = $this->getConversionRate(self::CURRENCY_FROM, self::CURRENCY_TO);

        // if ($conversionRate === null) {
        //     throw new \Exception('Conversion rate could not be fetched.');
        // }

        $conversionRate = $this->getConversionRate();

        // Berechne den Preis in Euro und füge 20% Marge hinzu
        $amountInEur = $amount * $conversionRate;
        $end = $this->applyMargin($amountInEur);

        $end = $this->roundDownToNearestTenCents($end);
        return $end;
    }

    public function parsePrice($price): float {
        $price = str_replace(',', '.', $price);
        return floatval($price);
    }

    /**
     * Получает курс конверсии между двумя валютами, используя кэширование.
     *
     * @param string $from Код валюты исходной валюты
     * @param string $to Код валюты целевой валюты
     * @return float|null Курс конверсии или null в случае ошибки
     */
    private function getConversionRateFromInternet(string $from, string $to): ?float
    {
        // Если курсы уже загружены, используем их
        if ($this->currencyRates === null) {
            $this->currencyRates = $this->fetchCurrencyRates($from);
        }

        // Возвращаем курс конверсии, если он существует
        return $this->currencyRates[$from][$to] ?? null;
    }

    /**
     * Получает все курсы валют с основного или резервного URL.
     *
     * @return array|null Массив курсов валют или null в случае ошибки
     */
    private function fetchCurrencyRates(string $from): ?array
    {
        // Пытаемся получить данные с основного URL
        try {
            $response = $this->httpClient->get(self::BASE_URL . $from . ".json");
            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            // Используем резервный URL, если основной не работает
            try {
                $response = $this->httpClient->get(self::FALLBACK_URL . $from . ".json");
                return json_decode($response->getBody(), true);
            } catch (RequestException $e) {
                // Возвращаем null, если оба URL не работают
                return null;
            }
        }
    }

    private function getConversionRate(): float
    {
        return 1 / $this->getExchangeRate();
    }

    /**
     * Fügt 20% Marge zum Betrag hinzu
     *
     * @param float $amount Betrag ohne Marge
     * @return float Betrag mit 20% Marge
     */
    private function applyMargin(float $amount): float
    {
        $amountWithMargin = $amount + ($amount * $this->getMargin() / 100);
        return round($amountWithMargin, 2);
    }

    /**
     * Преобразует чистую цену в брутто цену с учетом НДС.
     *
     * @param float $netAmount Чистая цена
     * @param float $vatRate Ставка НДС (обычная или пониженная)
     * @return float Брутто цена, округленная до двух знаков после запятой
     */
    public function convertNetToGross(float $netAmount, float $vatRate): float
    {
        // Вычисляем брутто цену с НДС
        $grossAmount = $netAmount * (1 + $vatRate);
        return round($grossAmount, 2); // Округляем до двух знаков после запятой
    }

    public function getMargin(): float
    {
        return $this->margin;
    }

    public function setMargin(float $margin): void
    {
        $this->margin = $margin;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): void
    {
        if ($exchangeRate <= 0) {
            $exchangeRate = 1;
        }

        $this->exchangeRate = $exchangeRate;
    }

    private function roundDownToNearestTenCents(float $price): float {
        return floor($price * 10) / 10;
    }
}
