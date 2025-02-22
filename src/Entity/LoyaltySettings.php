<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: LoyaltySettingsRepository::class)]
class LoyaltySettings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::FLOAT)]
    private float $points_to_money_ratio = 0.1; // 1 point = 0.1 currency

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPointsToMoneyRatio(): float
    {
        return $this->points_to_money_ratio;
    }

    public function setPointsToMoneyRatio(float $ratio): self
    {
        $this->points_to_money_ratio = $ratio;
        return $this;
    }

    public function convertPointsToMoney(int $points): float
    {
        return $points * $this->points_to_money_ratio;
    }

    public function convertMoneyToPoints(float $money): int
    {
        return (int) ($money / $this->points_to_money_ratio);
    }
}