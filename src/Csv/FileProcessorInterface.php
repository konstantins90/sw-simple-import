<?php

namespace App\Csv;

interface FileProcessorInterface {
    public const TAX_REDUCED = "01922b028bdc7100bab70c0185863536";
    public const CURRENCY_EUR = "b7d2554b0ce847cd82f3ac9bd1c0dfca";
    public const SALES_CHANNEL = "01922b03dc5c70c3a86c2df96a283ccf";
    public const MEDIA_FOLDER = "01922b028c247097a530c6cf62406bde";
    public function setRecords(array $records): void;
    public function setConfigFile(\Propel\Files $configFile): void;
    public function getPropertiesArray(): array;
    public function showLog(): void;
    public function mapProductProperties(): void;
    public function import(): void;
}