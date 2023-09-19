<?php

namespace App\Service;

use App\Entity\City;
use League\Csv\Reader;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCitiesService
{
    public function __construct(private CityRepository $cityRepo, private EntityManagerInterface $em)
    {
        
    }
    public function importCities(SymfonyStyle $io): void
    {
        $io->title('Importation des villes');

        $cities = $this->readCsvFile();

        $io->progressStart(count($cities));

        foreach($cities as $arrayCity){
            $io->progressAdvance();
            $city = $this->createOrUpdateCity($arrayCity);
            $this->em->persist($city);
        }

        $this->em->flush();

        $io->progressFinish();

        $io->success("Importation successfull");
    }

    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root_dir%/../import/cities.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateCity(array $arrayCity): City
    {
        $city = $this->cityRepo->findOneBy(['insee_code' => $arrayCity['insee_code']]);

        if(!$city){
            $city = new City();
        }

        $city->setInseeCode($arrayCity['insee_code'])
            ->setCityCode($arrayCity['city_code'])
            ->setZipCode($arrayCity['zip_code'])
            ->setLabel($arrayCity['label'])
            ->setLatitude($arrayCity['latitude'])
            ->setLongitude($arrayCity['longitude'])
            ->setDepartmentName($arrayCity['department_name'])
            ->setDepartmentNumber($arrayCity['department_number'])
            ->setRegionName($arrayCity['region_name'])
            ->setRegionGeojsonName($arrayCity['region_geojson_name']);

        return $city;
    }
}