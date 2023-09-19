<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $zip_code = null;

    #[ORM\Column(length: 50)]
    private ?string $city_name = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column(length: 50)]
    private ?string $department_name = null;

    #[ORM\Column]
    private ?int $department_number = null;

    #[ORM\Column(length: 50)]
    private ?string $region_name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipCode(): ?int
    {
        return $this->zip_code;
    }

    public function setZipCode(int $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->city_name;
    }

    public function setCityName(string $city_name): static
    {
        $this->city_name = $city_name;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDepartmentName(): ?string
    {
        return $this->department_name;
    }

    public function setDepartmentName(string $department_name): static
    {
        $this->department_name = $department_name;

        return $this;
    }

    public function getDepartmentNumber(): ?int
    {
        return $this->department_number;
    }

    public function setDepartmentNumber(int $department_number): static
    {
        $this->department_number = $department_number;

        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->region_name;
    }

    public function setRegionName(string $region_name): static
    {
        $this->region_name = $region_name;

        return $this;
    }
}
