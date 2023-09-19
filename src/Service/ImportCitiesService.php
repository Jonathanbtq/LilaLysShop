<?php

namespace App\Service;

use League\Csv\Reader;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCitiesService
{
    public function importCities(SymfonyStyle $io): void
    {
        $io->title('Importation des villes');

        $cities = $this->readCsvFile();

        foreach($cities as $city){
            dd($city);
        }
    }

    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root_dir%/../import/cities.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}