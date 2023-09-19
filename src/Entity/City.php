<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    private ?string $insee_code = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $city_code = null;

    #[ORM\Column]
    private ?int $zip_code = null;
    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    private ?string $longitude = null;

    #[ORM\Column(length: 50)]
    private ?string $department_name = null;

    #[ORM\Column(length: 50)]
    private ?string $department_number = null;

    #[ORM\Column(length: 50)]
    private ?string $region_name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $region_geojson_name = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Adresse::class)]
    private Collection $adresses;

    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInseeCode(): ?string
    {
        return $this->insee_code;
    }

    public function setInseeCode(string $insee_code): static
    {
        $this->insee_code = $insee_code;

        return $this;
    }

    public function getCityCode(): ?string
    {
        return $this->city_code;
    }

    public function setCityCode(string $city_code): static
    {
        $this->city_code = $city_code;

        return $this;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
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

    public function getDepartmentNumber(): ?string
    {
        return $this->department_number;
    }

    public function setDepartmentNumber(string $department_number): static
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

    public function getRegionGeojsonName(): ?string
    {
        return $this->region_geojson_name;
    }

    public function setRegionGeojsonName(string $region_geojson_name): static
    {
        $this->region_geojson_name = $region_geojson_name;

        return $this;
    }

    /**
     * @return Collection<int, Adresse>
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): static
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses->add($adress);
            $adress->setCity($this);
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): static
    {
        if ($this->adresses->removeElement($adress)) {
            // set the owning side to null (unless already changed)
            if ($adress->getCity() === $this) {
                $adress->setCity(null);
            }
        }

        return $this;
    }
}
