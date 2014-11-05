<?php

namespace Ferus\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CarRequestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('brand', 'text', array(
                'label' => 'Marque'
            ))
            ->add('model', 'text', array(
                'label' => 'Modèl'
            ))
            ->add('color', 'text', array(
                'label' => 'Couleur'
            ))
            ->add('plate', 'text', array(
                'label' => 'Plaque'
            ))
            ->add('actions', 'form_actions', [
                'buttons' => array(
                    'save' => [
                        'type' => 'submit',
                        'options' => [
                            'label' => 'Enregistrer',
                        ]
                    ],
                )
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\EventBundle\Entity\CarRequest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_eventbundle_carrequest';
    }
}
