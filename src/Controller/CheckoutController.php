<?php

namespace Mtt\CheckoutBundle\Controller;

use Mtt\CatalogBundle\Entity\Product;
use Mtt\CatalogBundle\Repository\ProductRepository;
use Mtt\CheckoutBundle\Model\Item;
use Mtt\Core\Interfaces\Catalog\Repository\ProductRepositoryInterface;
use Mtt\Core\Interfaces\Checkout\CheckoutInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class CheckoutController extends Controller
{
    /**
     * @var \Mtt\CheckoutBundle\Service\Checkout
     */
    protected $checkoutService;
    /**
     * @var ProductRepository
     */
    protected $productRepository;


    public function __construct(
        CheckoutInterface $checkoutService,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->checkoutService = $checkoutService;
        $this->productRepository = $productRepository;
    }

    public function add(Request $request, int $id, int $qty)
    {
        $product = $this->findProduct($id);

        $item = new Item($id, $product->getPrice(), $qty);

        $this->checkoutService->insert($item);

        $this->addFlash(
            'notice',
            'Product has been added to checkout!'
        );
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    /**
     * @param int $id
     * @return Product
     */
    protected function findProduct(int $id)
    {
        $product = $this->productRepository->find($id);
        if (null === $product) {
            throw new BadRequestHttpException("No product with id $id found");
        }
        return $product;
    }

}
