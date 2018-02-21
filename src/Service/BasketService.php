<?php
namespace Mtt\CheckoutBundle\Service;


use Lenius\Basket\IdentifierInterface;
use Lenius\Basket\StorageInterface;
use Mtt\CheckoutBundle\Model\Basket\Item;
use Mtt\Core\Interfaces\Checkout\CheckoutInterface;
use Mtt\Core\Interfaces\Checkout\ItemInterface;

class BasketService implements CheckoutInterface
{
    /**
     * @var \Lenius\Basket\Identifier\Cookie
     */
    protected $identifier;
    /**
     * @var \Mtt\CheckoutBundle\Model\Storage\Session
     */
    protected $storage;


    /**
     * Basket constructor.
     *
     * @param StorageInterface    $store      The interface for storing the cart data
     * @param IdentifierInterface $identifier The interface for storing the identifier
     */
    public function __construct(StorageInterface $storage, IdentifierInterface $identifier)
    {
        $this->storage = $storage;
        $this->identifier = $identifier;

        // Restore the cart from a saved version
        if (method_exists($this->storage, 'restore')) {
            $this->storage->restore();
        }

        // Let our storage class know which cart we're talking about
        $this->storage->setIdentifier($this->identifier->get());
    }

    /**
     * @return ItemInterface[]
     */
    public function contents(): ?array
    {
        return $this->storage->data();
    }


    /**
     * Insert an item into the basket.
     *
     *
     * @return string A unique item identifier
     */
    public function insert(ItemInterface $item)
    {

        if(true === $this->has($item->getIdentifier())){
            return;
        }

        $this->storage->insert($item);

        return $item->getIdentifier();
    }



    /**
     * Remove an item from the basket.
     *
     * @param string $identifier Unique item identifier
     *
     * @return void
     */
    public function remove($identifier)
    {
        $this->storage->remove($identifier);
    }

    /**
     * Destroy/empty the basket.
     *
     * @return void
     */
    public function destroy()
    {
        $this->storage->destroy();
    }

    /**
     * Check if the basket has a specific item.
     *
     * @param string $itemIdentifier The unique item identifier
     *
     * @return bool Yes or no?
     */
    public function has($itemIdentifier):bool
    {
        return $this->storage->has($itemIdentifier);
    }

    /**
     * Return a specific item object by identifier.
     *
     * @param string $itemIdentifier The unique item identifier
     *
     * @return Item Item object
     */
    public function find(string $itemIdentifier):?ItemInterface
    {
        return $this->storage->item($itemIdentifier);
    }


    /**
     * The total value of the basket.
     *
     * @param bool $includeTax Include tax on the total?
     *
     * @return float The total basket value
     */
    public function totalPrice():float
    {
        $total = 0;

        foreach ($this->contents() as $item) {
            $total += $item->total();
        }

        return (float) $total;
    }

    /**
     * The total number of items in the basket.
     *
     * @param bool $unique Just return unique items?
     *
     * @return int Total number of items
     */
    public function totalItems($unique = false):int
    {
        $total = 0;

        foreach ($this->contents() as $item) {
            $total += $unique ? 1 : $item->getQuantity();
        }

        return $total;
    }

    /**
     * Set the basket identifier, useful if restoring a saved basket.
     *
     * @param  mixed The identifier
     *
     * @return void
     */
    public function setIdentifier($identifier)
    {
        $this->storage->setIdentifier($identifier);
    }

}