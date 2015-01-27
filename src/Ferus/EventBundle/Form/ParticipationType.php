<?php

namespace Ferus\EventBundle\Form;

use Ferus\EventBundle\Form\DataTransformer\ArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParticipationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('studentId', 'hidden')
            ->add('firstName', 'hidden')
            ->add('lastName', 'hidden')
            ->add('email', 'hidden')
            ->add('comments', 'textarea', array(
                'label' => 'Commentaires',
                'required' => false,
            ))
            ->add(
                $builder->create('fields', 'hidden')
                ->addModelTransformer(new ArrayTransformer())
            )
            ->add(
                $builder->create('options', 'hidden')
                ->addModelTransformer(new ArrayTransformer())
            )
            ->add('paymentMethod', 'hidden')
            ->add('paymentAmount', 'hidden')
            ->add('depositMethod', 'hidden')
            ->add('depositAmount', 'hidden')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\EventBundle\Entity\Participation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_eventbundle_participation';
    }
}
