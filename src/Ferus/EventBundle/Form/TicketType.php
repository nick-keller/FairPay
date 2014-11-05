<?php

namespace Ferus\EventBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Nom'
            ))
            ->add('price', 'euro', array(
                'label' => 'Prix non-cotisant'
            ))
            ->add('priceContributor', 'euro', array(
                'label' => 'Prix cotisant'
            ))
            ->add('forceCheck', 'choice', array(
                'label' => 'Type de paiement',
                'expanded' => true,
                'choices' => array(
                    'Libre',
                    'Par chèque'
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
            'data_class' => 'Ferus\EventBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_eventbundle_ticket';
    }
}
