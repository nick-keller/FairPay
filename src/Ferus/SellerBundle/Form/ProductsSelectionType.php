<?php

namespace Ferus\SellerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductsSelectionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', 'barcode', array(
                'label' => 'Etudiant',
            ))
            ->add('amount', 'hidden', array(
                'attr' => array(
                    'data-input' => 'amount',
                )
            ))
            ->add('cause', 'hidden', array(
                'attr' => array(
                    'data-input' => 'cause',
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\SellerBundle\Entity\ProductsSelection'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_sellerbundle_product_selection';
    }
}
