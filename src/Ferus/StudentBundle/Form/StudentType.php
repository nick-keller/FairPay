<?php

namespace Ferus\StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StudentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'integer', array(
                'label' => 'Numéro',
                'attr' => array(
                    'help_text' => 'Numéro figurant sur la carte étudiante',
                    'input_group' => array(
                        'prepend' => '.icon-barcode',
                    ),
                )
            ))
            ->add('firstName', 'text', array(
                'label' => 'Prénom',
            ))
            ->add('lastName', 'text', array(
                'label' => 'Nom',
            ))
            ->add('class', 'text', array(
                'label' => 'Promo',
            ))
            ->add('email', 'text', array(
                'label' => 'Email ESIEE',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Généré automatiquement',
                )
            ))
            ->add('login', 'text', array(
                'label' => 'Login ESIEE',
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Généré automatiquement',
                )
            ))
            ->add('isContributor', 'choice', array(
                'label' => 'Statut',
                'choices' => ['Non cotsant', 'Cotisant'],
                'expanded' => true,
            ))
            ->add('actions', 'form_actions', [
                'buttons' => array(
                    'save' => [
                        'type' => 'submit',
                        'options' => [
                            'label' => 'Enregistrer',
                            'attr' => [
                                'data-loading-text' => 'Création...'
                            ]
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
            'data_class' => 'Ferus\StudentBundle\Entity\Student'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_studentbundle_student';
    }
}
