<?php

namespace Ferus\AccountBundle\Form\Type;

use Ferus\AccountBundle\Form\DataTransformer\BarcodeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;

class BarcodeType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new BarcodeTransformer($this->om, $options['data']);
        $builder->addModelTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'attr' => array(
                'input_group' => array(
                    'prepend' => '.icon-barcode',
                ),
                'help_text' => 'Numéro figurant sur la carte étudiante',
            ),
            'data' => 'account'
        ));

        $resolver->setAllowedValues(array(
            'data' => array('account', 'owner'),
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'barcode';
    }
} 