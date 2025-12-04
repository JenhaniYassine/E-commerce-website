<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SettingsRepository::class)
 * @ORM\Table(name="settings")
 */
class Settings
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $primaryColor;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $secondaryColor;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $brandingText;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentGateway;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $deliveryMethods = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adminEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primaryColor;
    }

    public function setPrimaryColor(?string $primaryColor): self
    {
        $this->primaryColor = $primaryColor;

        return $this;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(?string $secondaryColor): self
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }

    public function getBrandingText(): ?string
    {
        return $this->brandingText;
    }

    public function setBrandingText(?string $brandingText): self
    {
        $this->brandingText = $brandingText;

        return $this;
    }

    public function getPaymentGateway(): ?string
    {
        return $this->paymentGateway;
    }

    public function setPaymentGateway(?string $paymentGateway): self
    {
        $this->paymentGateway = $paymentGateway;

        return $this;
    }

    public function getDeliveryMethods(): ?array
    {
        return $this->deliveryMethods;
    }

    public function setDeliveryMethods(?array $deliveryMethods): self
    {
        $this->deliveryMethods = $deliveryMethods;

        return $this;
    }

    public function getAdminEmail(): ?string
    {
        return $this->adminEmail;
    }

    public function setAdminEmail(?string $adminEmail): self
    {
        $this->adminEmail = $adminEmail;

        return $this;
    }
}
