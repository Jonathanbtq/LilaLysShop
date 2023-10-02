<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CartRepository;
use App\Repository\PanierProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $nb_products = null;

    #[ORM\Column]
    private ?float $total_price = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: PanierProduit::class)]
    private Collection $panierProduits;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    private ?CodePromo $codepromo = null;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNbProducts(): ?int
    {
        return $this->nb_products;
    }

    public function setNbProducts(int $nb_products): static
    {
        $this->nb_products = $nb_products;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): static
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->add($panierProduit);
            $panierProduit->setCart($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            // set the owning side to null (unless already changed)
            if ($panierProduit->getCart() === $this) {
                $panierProduit->setCart(null);
            }
        }

        return $this;
    }

    public function getCodepromo(): ?CodePromo
    {
        return $this->codepromo;
    }

    public function setCodepromo(?CodePromo $codepromo): static
    {
        $this->codepromo = $codepromo;

        return $this;
    }

    public function getCartPrice(PanierProduitRepository $panierRepo)
    {
        $products = $panierRepo->findBy(['cart' => $this->getId()]);
        $sommPrice = 0;

        foreach($products as $produit){
            $sommPrice += $produit->getPrice();
        }
        $this->setTotalPrice($sommPrice);
        return $sommPrice;
    }
}
