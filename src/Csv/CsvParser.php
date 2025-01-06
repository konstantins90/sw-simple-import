<?php

namespace App\Csv;

use League\Csv\Reader;

class CsvParser
{
    private Reader $csv;

    public function __construct(string $filePath)
    {
        $formatter = fn (array $row): array => array_map(trim(...), $row);

        $this->csv = Reader::createFromPath($filePath, 'r');
        $this->csv->setHeaderOffset(0);
        $this->csv->setDelimiter(';');
        $this->csv->addFormatter($formatter);
    }

    public function getRecords(): iterable
    {
        return $this->csv->getRecords();
    }
}
