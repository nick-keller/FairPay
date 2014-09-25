<?php

namespace Ferus\MailBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Nom du template',
            ))
            ->add('subject', 'text', array(
                'label' => 'Sujet',
            ))
            ->add('text', 'textarea', array(
                'label' => 'Message',
            ))
            ->add('firstWaveAuth', 'entity', array(
                'label' => 'Autorisation',
                'class' => 'Ferus\MailBundle\Entity\Authority',
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('firstWaveCC', 'entity', array(
                'label' => 'En copie',
                'class' => 'Ferus\MailBundle\Entity\Authority',
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('secondWaveAuth', 'entity', array(
                'label' => 'Autorisation',
                'class' => 'Ferus\MailBundle\Entity\Authority',
                'multiple' => true,
                'expanded' => true,
            ))
            ->add('secondWaveCC', 'entity', array(
                'label' => 'En copie',
                'class' => 'Ferus\MailBundle\Entity\Authority',
                'multiple' => true,
                'expanded' => true,
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
            'data_class' => 'Ferus\MailBundle\Entity\Template'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_mailbundle_template';
    }
}
