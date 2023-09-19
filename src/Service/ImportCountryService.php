<?php

namespace App\Service;

use App\Entity\Pays;
use League\Csv\Reader;
use Doctrine\ORM\EntityManager;
use App\Repository\PaysRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportCountryService
{
    public function __construct(private PaysRepository $paysRepo, private EntityManagerInterface $em)
    {
        
    }

    public function importCountry(SymfonyStyle $io): void
    {
        $io->title('Importation des Pays');

        $pays = $this->readCsvFile();

        $io->progressStart(count($pays));

        foreach($pays as $arrayPays){
            $io->progressAdvance();
            $pays = $this->createOrUpdateCountry($arrayPays);
            $this->em->persist($pays);
        }

        $this->em->flush();

        $io->progressFinish();

        $io->success("Importation successfull");
    }

    private function readCsvFile(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root_dir%/../import/pays.csv', 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateCountry(array $arrayPays): Pays
    {
        $pays = $this->paysRepo->findOneBy(['code' => $arrayPays['code']]);

        if(!$pays){
            $pays = new Pays();
        }

        $pays->setNom($arrayPays['nom'])
            ->setNomAlpha($arrayPays['nom_alpha'])
            ->setCode($arrayPays['code'])
            ->setArticle($arrayPays['article'])
            ->setNomLong($arrayPays['nom_long'])
            ->setCapitale($arrayPays['capitale']);

        return $pays;
    }
}