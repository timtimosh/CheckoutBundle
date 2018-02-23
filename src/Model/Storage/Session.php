<?php

namespace Mtt\CheckoutBundle\Model\Storage;

use Lenius\Basket\Storage;
use Mtt\CheckoutBundle\Model\ItemInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Session extends Storage\Runtime
{
    const BASKET_CART_SESSION_KEY = 'mtt_checkout_basket';

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * The Session store constructor.
     */
    public function restore()
    {
        if ($basketData = $this->session->get(self::BASKET_CART_SESSION_KEY)) {
            static::$cart = unserialize($basketData);
        }
    }

    public function remove($id)
    {
        unset(static::$cart[$this->id][$id]);
        $this->updateBasketSession(static::$cart);
    }

    public function destroy()
    {
        static::$cart[$this->id] = [];
        $this->updateBasketSession(static::$cart);
    }

    public function insert(ItemInterface $item)
    {
        if (!empty(static::$cart[$this->id][$item->identifier])) {
           $this->update($item);
        } else {
            static::$cart[$this->id][$item->identifier] = $item;
        }
        $this->updateBasketSession(static::$cart);
    }

    protected function update(ItemInterface $item){
        $itemExists = static::$cart[$this->id][$item->identifier];
        $itemExists->setQuantity($item->getQuantity()+ $itemExists->getQuantity());
    }

    protected function updateBasketSession($basket)
    {
        $this->session->set(self::BASKET_CART_SESSION_KEY, serialize($basket));
    }

}
