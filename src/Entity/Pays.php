<?php

namespace App\Entity;

use App\Repository\PaysRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaysRepository::class)]
class Pays
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_alpha = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $article = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_long = null;

    #[ORM\Column(length: 255)]
    private ?string $capitale = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNomAlpha(): ?string
    {
        return $this->nom_alpha;
    }

    public function setNomAlpha(string $nom_alpha): static
    {
        $this->nom_alpha = $nom_alpha;

        return $this;
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

    public function getArticle(): ?string
    {
        return $this->article;
    }

    public function setArticle(string $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getNomLong(): ?string
    {
        return $this->nom_long;
    }

    public function setNomLong(string $nom_long): static
    {
        $this->nom_long = $nom_long;

        return $this;
    }

    public function getCapitale(): ?string
    {
        return $this->capitale;
    }

    public function setCapitale(string $capitale): static
    {
        $this->capitale = $capitale;

        return $this;
    }
}
