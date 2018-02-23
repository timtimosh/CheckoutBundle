<?php

namespace Mtt\CheckoutBundle\Model;

interface ItemInterface
{
    public function getIdentifier(): string;

    public function getProductId(): int;

    public function setSku(?string $sku);

    public function getSku(): ?string;

    public function getPrice(): float;

    public function setPrice(float $price);

    public function getName(): ?string;

    public function setName(?string $name);

    public function getQuantity(): int;

    public function setQuantity(int $quantity);

    public function total(): float;
}
