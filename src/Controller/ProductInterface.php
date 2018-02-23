<?php

namespace Mtt\CheckoutBundle\Controller;

interface ProductInterface
{
    public function getSku(): ?string;
    public function setSku(?string $sku);
    public function getPrice():float;
    public function setPrice(float $price);
    public function getName():?string;
    public function setName(?string $name);
}
