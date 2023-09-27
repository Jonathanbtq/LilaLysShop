<?php

namespace App\Entity;

use App\Repository\ProductImgRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductImgRepository::class)]
class ProductImg
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prdname = null;

    #[ORM\ManyToOne(inversedBy: 'productImgs')]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrdname(): ?string
    {
        return $this->prdname;
    }

    public function setPrdName(string $prdname): static
    {
        $this->prdname = $prdname;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
