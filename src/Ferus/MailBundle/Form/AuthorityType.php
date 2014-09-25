<?php

namespace Ferus\MailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AuthorityType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'Email'
            ))
            ->add('name', 'text', array(
                'label' => 'Nom'
            ))
            ->add('okMessage', 'tag', array(
                'label' => 'Messages de validation'
            ))
            ->add('noMessage', 'tag', array(
                'label' => 'Messages de refus'
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
            'data_class' => 'Ferus\MailBundle\Entity\Authority'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_mailbundle_authority';
    }
}
