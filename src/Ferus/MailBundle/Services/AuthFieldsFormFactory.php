<?php


namespace Ferus\MailBundle\Services;

use Doctrine\ORM\EntityManager;
use Ferus\MailBundle\Entity\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class AuthFieldsFormFactory 
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var FormFactory
     */
    private $formFactory;

    function __construct($em, $formFactory)
    {
        $this->em          = $em;
        $this->formFactory = $formFactory;
    }

    public function createFromTemplate(Template $template)
    {
        $fields = $template->getCustomFields();
        $constraints = array();

        foreach($fields as $field){
            $slug = preg_replace('#[^a-z0-9_]#', '', strtolower(str_replace(' ', '_', $field)));
            $constraints[$slug] = array( new NotBlank() );
        }

        $form = $this->formFactory->createBuilder(
            'form',
            null,
            array( 'constraints' => new Collection($constraints) )
        );

        foreach($fields as $field){
            $form->add(preg_replace('#[^a-z0-9_]#', '', strtolower(str_replace(' ', '_', $field))), 'text', array(
                'label' => ucfirst($field),
            ));
        }

        $form->add('actions', 'form_actions', [
            'buttons' => array(
                'save' => [
                    'type' => 'submit',
                    'options' => [
                        'label' => 'Enregistrer',
                    ]
                ],
            )
        ]);

        return $form->getForm();
    }
} 