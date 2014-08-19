<?php

namespace Ferus\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'choice', array(
                'label' => 'Droits',
                'multiple' => true,
                'expanded' => true,
                'choices' => array(
                    'ROLE_ADMIN'=>'ROLE_ADMIN',
                    'ROLE_USER_ADMIN'=>'ROLE_USER_ADMIN',
                    'ROLE_STUDENT_ADMIN'=>'ROLE_STUDENT_ADMIN',
                    'ROLE_SELLER_ADMIN'=>'ROLE_SELLER_ADMIN',
                    'ROLE_ACCOUNT_ADMIN'=>'ROLE_ACCOUNT_ADMIN',
                    'ROLE_TRANSACTION_ADMIN'=>'ROLE_TRANSACTION_ADMIN',
                    'ROLE_WITHDRAWAL_ADMIN'=>'ROLE_WITHDRAWAL_ADMIN',
                    'ROLE_SUPER_ADMIN'=>'ROLE_SUPER_ADMIN',
                ),
                'translation_domain' => 'roles_long'
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
            'data_class' => 'Ferus\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_userbundle_user';
    }
}
