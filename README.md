# CheckoutBundle for ecomerce

It`s only purpose to save product into a basket. The only can be saved to session for now.
The main ideology of such a naked)) bundle is to give the oportunity of integrating it into another bundle or with another (shipping, payment, etc).


###What it can do:
 - remove products from basket 
 - add product to basket
 - display basket items
 - display each item price
 - display total items and total items price.
 
 
that`s all! =)

## How to Install
No spesial installation options required.

**see:**  _mtt_checkout.basket_service_ - the main basket service, also you may use 2 routes to add and remove items from basket here _mtt/checkout-bundle/src/Resources/config/routing.yml_
    
     
     mtt_checkout.checkout_controller_action_service:
         class: Mtt\CheckoutBundle\Controller\CheckoutActionsController
         tags: ['controller.service_arguments']
         arguments:
             - '@mtt_checkout.basket_service'
             - '@mtt_catalog.product_repository.service' 
     
Add above code to you service.yml and rewrite mtt_catalog.product_repository.service by your product repository so Checkout could find product by id.
also implement CheckoutBundle ProductInterface into your Product entity. [implements \Mtt\CheckoutBundle\Controller\ProductInterface]      
     
             
