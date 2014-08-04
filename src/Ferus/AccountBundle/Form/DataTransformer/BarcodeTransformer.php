<?php

namespace Ferus\AccountBundle\Form\DataTransformer;

use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Ferus\AccountBundle\Entity\Account;
use Ferus\StudentBundle\Entity\Student;

class BarcodeTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    private $data;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om, $data)
    {
        $this->om = $om;
        $this->data = $data;
    }

    /**
     * Transforms an object (Student or Account) to a string (barcode).
     *
     * @param  Account|Student|null $item
     * @return string
     */
    public function transform($item)
    {
        if($item instanceof Student)
            return $item->getId();

        if($item instanceof Account)
            return $item->getStudent()->getId();

        return "";
    }

    /**
     * Transforms a string (barcode) to an object (Student or Account).
     *
     * @param  string $barcode
     * @return Student|Account|null
     * @throws TransformationFailedException if object (Student or Account) is not found.
     */
    public function reverseTransform($barcode)
    {
        if (!$barcode) return null;

        if($this->data == 'student'){
            try{
                $student = $this->om
                    ->getRepository('FerusStudentBundle:Student')
                    ->findOneById($barcode)
                ;
            }
            catch(NoResultException $e){
                throw new TransformationFailedException('Cet étudiant n\'existe pas.');
            }

            return $student;
        }

        try{
            $account = $this->om
                ->getRepository('FerusAccountBundle:Account')
                ->findOneByStudentId($barcode);
        }
        catch(NoResultException $e){
            throw new TransformationFailedException('Ce compte n\'existe pas.');
        }

        return $account;
    }
} 