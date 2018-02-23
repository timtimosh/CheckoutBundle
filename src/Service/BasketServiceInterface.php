<?php
namespace Mtt\CheckoutBundle\Service;

use Mtt\CheckoutBundle\Model\ItemInterface;

interface BasketServiceInterface
{
    public function contents(): ?array;


    public function insert(ItemInterface $item);


    public function remove(string $identifier);


    public function destroy();

    public function has(string $itemIdentifier):bool;


    public function find(string $itemIdentifier):?ItemInterface;


    public function totalPrice() :float;


    public function totalItems(bool $unique = false):int;
}
