<?php

namespace App\Entity;

use App\Repository\PaymentMethodRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PaymentMethodRepository::class)
 */
class PaymentMethod
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="paymentMethods")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardType; // 'visa', 'mastercard', 'amex', etc.

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cardholderName;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $lastFourDigits;

    /**
     * @ORM\Column(type="integer")
     */
    private $expiryMonth;

    /**
     * @ORM\Column(type="integer")
     */
    private $expiryYear;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    public function setCardType(string $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function getCardholderName(): ?string
    {
        return $this->cardholderName;
    }

    public function setCardholderName(string $cardholderName): self
    {
        $this->cardholderName = $cardholderName;

        return $this;
    }

    public function getLastFourDigits(): ?string
    {
        return $this->lastFourDigits;
    }

    public function setLastFourDigits(string $lastFourDigits): self
    {
        $this->lastFourDigits = $lastFourDigits;

        return $this;
    }

    public function getExpiryMonth(): ?int
    {
        return $this->expiryMonth;
    }

    public function setExpiryMonth(int $expiryMonth): self
    {
        $this->expiryMonth = $expiryMonth;

        return $this;
    }

    public function getExpiryYear(): ?int
    {
        return $this->expiryYear;
    }

    public function setExpiryYear(int $expiryYear): self
    {
        $this->expiryYear = $expiryYear;

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMaskedCardNumber(): string
    {
        return '**** **** **** ' . $this->lastFourDigits;
    }

    public function getExpiryDate(): string
    {
        return sprintf('%02d/%d', $this->expiryMonth, $this->expiryYear);
    }
}
