<?php

namespace Mtt\CheckoutBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Mtt\CheckoutBundle\Model\Basket\Item;
use Mtt\CheckoutBundle\Model\ItemInterface;
use Mtt\CheckoutBundle\Service\BasketServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class CheckoutActionsController extends Controller
{
    /**
     * @var \Mtt\CheckoutBundle\Service\BasketService
     */
    protected $basketService;

    protected $productRepository;


    public function __construct(
        BasketServiceInterface $basketService,
        EntityRepository $productRepository
    )
    {
        $this->basketService = $basketService;
        $this->productRepository = $productRepository;
    }

    public function removeItemAction(Request $request, string $identifier)
    {
        if ($this->basketService->has($identifier)) {
            $this->basketService->remove($identifier);

            $this->addFlash(
                'notice',
                $this->get('translator')->trans('Product has been removed to checkout!')
            );
        }

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    public function addItemAction(Request $request, int $id, int $qty)
    {
        $product = $this->findProduct($id);
        $item = $this->createBasketItemFromProductEntity($id, $qty, $product);
        $this->basketService->insert($item);

        $this->addFlash(
            'notice',
            $this->get('translator')->trans('Product has been added to checkout!')
        );
        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }

    protected function createBasketItemFromProductEntity(int $id, int $qty, ProductInterface $product): ItemInterface
    {
        $item = new \Mtt\CheckoutBundle\Model\Item(
            $id,
            $product->getPrice(),
            $qty
        );
        $item->setName($product->getName());
        $item->setSku($product->getSku());
        return $item;
    }

    /**
     * @param int $id
     * @return ProductInterface
     */
    protected function findProduct(int $id): ProductInterface
    {
        $product = $this->productRepository->find($id);
        if (null === $product) {
            throw new BadRequestHttpException("No product with id $id found");
        }
        return $product;
    }

}
