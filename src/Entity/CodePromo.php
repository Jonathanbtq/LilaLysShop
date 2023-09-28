<?php

namespace App\Entity;

use App\Repository\CodePromoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CodePromoRepository::class)]
class CodePromo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\Column]
    private ?float $pourcentage_reduction = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(?\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getPourcentageReduction(): ?float
    {
        return $this->pourcentage_reduction;
    }

    public function setPourcentageReduction(float $pourcentage_reduction): static
    {
        $this->pourcentage_reduction = $pourcentage_reduction;

        return $this;
    }
}
