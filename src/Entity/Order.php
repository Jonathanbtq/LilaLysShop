<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse_fact = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $fact_mail = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $work_modele = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $is_promo = null;

    #[ORM\Column]
    private ?float $total_discount = null;

    #[ORM\Column]
    private ?int $nb_product = null;

    #[ORM\Column]
    private ?float $ship_price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $order_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_bon_commande = null;

    #[ORM\Column]
    private ?float $price_bfr_taxe = null;

    #[ORM\Column]
    private ?float $sous_total = null;

    #[ORM\Column]
    private ?float $sous_total_taxe = null;

    #[ORM\Column]
    private ?float $total_price = null;

    #[ORM\Column(length: 255)]
    private ?string $type_taxe_local = null;

    #[ORM\Column(length: 255)]
    private ?string $client_name = null;

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

    public function getAdresseFact(): ?string
    {
        return $this->adresse_fact;
    }

    public function setAdresseFact(?string $adresse_fact): static
    {
        $this->adresse_fact = $adresse_fact;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getFactMail(): ?string
    {
        return $this->fact_mail;
    }

    public function setFactMail(string $fact_mail): static
    {
        $this->fact_mail = $fact_mail;

        return $this;
    }

    public function getWorkModele(): ?string
    {
        return $this->work_modele;
    }

    public function setWorkModele(?string $work_modele): static
    {
        $this->work_modele = $work_modele;

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

    public function isIsPromo(): ?bool
    {
        return $this->is_promo;
    }

    public function setIsPromo(bool $is_promo): static
    {
        $this->is_promo = $is_promo;

        return $this;
    }

    public function getTotalDiscount(): ?float
    {
        return $this->total_discount;
    }

    public function setTotalDiscount(float $total_discount): static
    {
        $this->total_discount = $total_discount;

        return $this;
    }

    public function getNbProduct(): ?int
    {
        return $this->nb_product;
    }

    public function setNbProduct(int $nb_product): static
    {
        $this->nb_product = $nb_product;

        return $this;
    }

    public function getShipPrice(): ?float
    {
        return $this->ship_price;
    }

    public function setShipPrice(float $ship_price): static
    {
        $this->ship_price = $ship_price;

        return $this;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->order_date;
    }

    public function setOrderDate(\DateTimeInterface $order_date): static
    {
        $this->order_date = $order_date;

        return $this;
    }

    public function getDateBonCommande(): ?\DateTimeInterface
    {
        return $this->date_bon_commande;
    }

    public function setDateBonCommande(\DateTimeInterface $date_bon_commande): static
    {
        $this->date_bon_commande = $date_bon_commande;

        return $this;
    }

    public function getPriceBfrTaxe(): ?float
    {
        return $this->price_bfr_taxe;
    }

    public function setPriceBfrTaxe(float $price_bfr_taxe): static
    {
        $this->price_bfr_taxe = $price_bfr_taxe;

        return $this;
    }

    public function getSousTotal(): ?float
    {
        return $this->sous_total;
    }

    public function setSousTotal(float $sous_total): static
    {
        $this->sous_total = $sous_total;

        return $this;
    }

    public function getSousTotalTaxe(): ?float
    {
        return $this->sous_total_taxe;
    }

    public function setSousTotalTaxe(float $sous_total_taxe): static
    {
        $this->sous_total_taxe = $sous_total_taxe;

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

    public function getTypeTaxeLocal(): ?string
    {
        return $this->type_taxe_local;
    }

    public function setTypeTaxeLocal(string $type_taxe_local): static
    {
        $this->type_taxe_local = $type_taxe_local;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->client_name;
    }

    public function setClientName(string $client_name): static
    {
        $this->client_name = $client_name;

        return $this;
    }
}
