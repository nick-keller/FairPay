<?php

namespace Ferus\AccountBundle\Form\DataTransformer;

use Doctrine\ORM\NoResultException;
use Ferus\SellerBundle\Entity\Seller;
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
     * @param $data
     */
    public function __construct(ObjectManager $om, $data)
    {
        $this->om = $om;
        $this->data = $data;
    }

    /**
     * Transforms an object (Student or Account) to a string (barcode).
     *
     * @param  Account|Student|Seller|null $item
     * @return string
     */
    public function transform($item)
    {
        if($item instanceof Student || $item instanceof Seller)
            return $item->getBarcode();

        if($item instanceof Account)
            return $item->getOwner()->getBarcode();

        return '';
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

        if($this->data == 'owner'){
            try{
                if(preg_match('/^\d+$/', $barcode)){
                    $owner = $this->om
                        ->getRepository('FerusStudentBundle:Student')
                        ->findOneById($barcode)
                    ;
                }
                else{
                    $owner = $this->om
                        ->getRepository('FerusSellerBundle:Seller')
                        ->findOneByBarcode($barcode)
                    ;
                }
            }
            catch(NoResultException $e){
                throw new TransformationFailedException('Cet étudiant n\'existe pas.');
            }

            return $owner;
        }

        try{
            $account = $this->om
                ->getRepository('FerusAccountBundle:Account')
                ->findOneByBarcode($barcode);
        }
        catch(NoResultException $e){
            throw new TransformationFailedException('Ce compte n\'existe pas.');
        }

        return $account;
    }
} 