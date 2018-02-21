<?php

namespace Mtt\CheckoutBundle\Form;

class OnePageCheckoutFormBuilderService
{
    protected $form;

    public function __construct()
    {

    }

    public function build(){
        $this->form = $this->createFormBuilder($data)
            ->add('query', 'text')
            ->add('category', 'choice',
                array('choices' => array(
                    'judges'   => 'Judges',
                    'interpreters' => 'Interpreters',
                    'attorneys'   => 'Attorneys',
                )))
            ->getForm();

        return $this->form;
    }

}
