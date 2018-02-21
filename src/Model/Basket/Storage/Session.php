<?php

namespace Mtt\CheckoutBundle\Model\Basket\Storage;

use Lenius\Basket\Storage;
use Mtt\Core\Interfaces\Checkout\ItemInterface;

class Session extends Storage\Session
{

    public function insert(ItemInterface $item)
    {
        static::$cart[$this->id][$item->identifier] = $item;
    }

}
