<?php

namespace Ferus\TransactionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WithdrawalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('account', 'barcode', array(
                'label' => 'Compte',
            ))
            ->add('amount', 'euro', array(
                'label' => 'Montant',
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
            'data_class' => 'Ferus\TransactionBundle\Entity\Withdrawal'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_transactionbundle_withdrawal';
    }
}
