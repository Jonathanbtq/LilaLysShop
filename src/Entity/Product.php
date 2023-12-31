<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $visible_image = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $list_price = null;

    #[ORM\Column]
    private ?float $price_extra = null;

    #[ORM\Column]
    private ?bool $has_discounted_price = null;

    #[ORM\Column]
    private ?int $stock_quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $product_type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $product_image = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'id_produit', targetEntity: PanierProduit::class)]
    private Collection $panierProduits;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductImg::class)]
    private Collection $productImgs;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
        $this->productImgs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isVisibleImage(): ?bool
    {
        return $this->visible_image;
    }

    public function setVisibleImage(bool $visible_image): static
    {
        $this->visible_image = $visible_image;

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

    public function getListPrice(): ?float
    {
        return $this->list_price;
    }

    public function setListPrice(float $list_price): static
    {
        $this->list_price = $list_price;

        return $this;
    }

    public function getPriceExtra(): ?float
    {
        return $this->price_extra;
    }

    public function setPriceExtra(float $price_extra): static
    {
        $this->price_extra = $price_extra;

        return $this;
    }

    public function isHasDiscountedPrice(): ?bool
    {
        return $this->has_discounted_price;
    }

    public function setHasDiscountedPrice(bool $has_discounted_price): static
    {
        $this->has_discounted_price = $has_discounted_price;

        return $this;
    }

    public function getStockQuantity(): ?int
    {
        return $this->stock_quantity;
    }

    public function setStockQuantity(int $stock_quantity): static
    {
        $this->stock_quantity = $stock_quantity;

        return $this;
    }

    public function getProductType(): ?string
    {
        return $this->product_type;
    }

    public function setProductType(string $product_type): static
    {
        $this->product_type = $product_type;

        return $this;
    }

    public function getProductImage(): ?string
    {
        return $this->product_image;
    }

    public function setProductImage(?string $product_image): static
    {
        $this->product_image = $product_image;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

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
            $panierProduit->setIdProduit($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            // set the owning side to null (unless already changed)
            if ($panierProduit->getIdProduit() === $this) {
                $panierProduit->setIdProduit(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ProductImg>
     */
    public function getProductImgs(): Collection
    {
        return $this->productImgs;
    }

    public function addProductImg(ProductImg $productImg): static
    {
        if (!$this->productImgs->contains($productImg)) {
            $this->productImgs->add($productImg);
            $productImg->setProduct($this);
        }

        return $this;
    }

    public function removeProductImg(ProductImg $productImg): static
    {
        if ($this->productImgs->removeElement($productImg)) {
            // set the owning side to null (unless already changed)
            if ($productImg->getProduct() === $this) {
                $productImg->setProduct(null);
            }
        }

        return $this;
    }
}
