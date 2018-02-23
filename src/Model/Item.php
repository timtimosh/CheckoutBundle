<?php
declare(strict_types=1);

namespace Mtt\CheckoutBundle\Model;


class Item implements ItemInterface
{
    protected $identifier;
    protected $productId;
    protected $sku;
    protected $price;
    protected $name;
    protected $quantity;

    /**
     * Item constructor.
     * @param int $productId product unique id
     * @param float $price price
     * @param int $quantity qty
     */
    public function __construct(int $productId, float $price, int $quantity)
    {
        $this->productId = $productId;
        $this->setPrice($price);
        $this->setQuantity($quantity);

        $this->identifier = $this->generateIdentifier();
    }

    /**
     * Return the value of protected methods.
     *
     * @param any $param
     *
     * @return mixed
     */
    public function __get($param)
    {
        $method = 'get' . ucfirst($param);
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        throw new \Exception('No ' . $param . ' found!');
    }

    /**
     * Update data array using set magic method.
     *
     * @param string $param The key to set
     * @param mixed $value The value to set $param to
     */
    public function __set($param, $value)
    {
        $method = 'set' . ucfirst($param);
        if (method_exists($this, $method)) {
            return $this->$method($value);
        }
        throw new \Exception('No ' . $param . ' found!');
    }


    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getProductId(): int
    {
        return $this->productId;
    }


    public function setSku(?string $sku)
    {
        $this->sku = $sku;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }


    public function getPrice(): float
    {
        return $this->price;
    }


    public function setPrice(float $price)
    {
        $this->price = $price;
    }


    public function getName(): ?string
    {
        return $this->name;
    }


    public function setName(?string $name)
    {
        $this->name = $name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }


    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Return the total of the item, with or without tax.
     *
     * @param bool $includeTax Whether or not to include tax
     *
     * @return float The total, as a float
     */
    public function total(): float
    {
        $price = $this->price;

        /* if ($this->hasOptions()) {
             foreach ($this->data['options'] as $item) {
                 if (array_key_exists('price', $item)) {
                     $price += $item['price'];
                 }
             }
         }

         if ($includeTax) {
             $price = $this->tax->add($price);
         }*/

        return (float)($price * $this->quantity);
    }

    /**
     * By this ID, the system determines whether to add a new product to the cart
     * or update the existing one
     * @param int $length
     * @return string
     */
    protected function generateIdentifier(int $length = 10): string
    {
        return md5($this->productId . ' this may containt variants cose they have another price');
    }

}