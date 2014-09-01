<?php

namespace Ferus\StudentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CsvImportType extends AbstractType
{
    private $router;

    function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('csv', 'textarea', array(
                'label' => 'Contenu',
                'attr' => array(
                    'placeholder' => 'Id;Nom;Prénom;Promo'
                )
            ))
            ->add('actions', 'form_actions', [
                'buttons' => array(
                    'save' => [
                        'type' => 'submit',
                        'options' => [
                            'label' => 'Importer',
                        ]
                    ],
                )
            ])
            ->setAction($this->router->generate('student_admin_import'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ferus\StudentBundle\Entity\CsvImport'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ferus_studentbundle_csv';
    }
}
