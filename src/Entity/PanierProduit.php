<?php

namespace App\Entity;

use App\Repository\PanierProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierProduitRepository::class)]
class PanierProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    private ?User $id_client = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    private ?Product $id_produit = null;

    #[ORM\Column]
    private ?float $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getIdClient(): ?User
    {
        return $this->id_client;
    }

    public function setIdClient(?User $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getIdProduit(): ?Product
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Product $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }
}
