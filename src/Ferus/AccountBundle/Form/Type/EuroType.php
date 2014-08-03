<?php

namespace Ferus\AccountBundle\Form\Type;

use Ferus\AccountBundle\Form\DataTransformer\MoneyTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;


class EuroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new MoneyTransformer();
        $builder->addViewTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'input_group' => array(
                    'prepend' => '.icon-euro',
                ),
                'data-type' => 'money'
            )
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'euro';
    }
} 