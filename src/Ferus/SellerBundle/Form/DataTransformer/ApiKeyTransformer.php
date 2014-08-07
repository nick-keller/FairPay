<?php


namespace Ferus\SellerBundle\Form\DataTransformer;

use Ferus\SellerBundle\Entity\Seller;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;

class ApiKeyTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (seller) to a string (api_key).
     *
     * @param  Seller|null $seller
     * @return string
     */
    public function transform($seller)
    {
        if (null === $seller) {
            return "";
        }

        return $seller->getApiKey();
    }

    /**
     * Transforms a string (api_key) to an object (seller).
     *
     * @param  string $api_key
     *
     * @return Seller|null
     *
     * @throws TransformationFailedException if object (seller) is not found.
     */
    public function reverseTransform($api_key)
    {
        if (!$api_key) {
            return null;
        }

        $seller = $this->om
            ->getRepository('FerusSellerBundle:Seller')
            ->findOneBy(array('apiKey' => $api_key))
        ;

        if (null === $seller) {
            throw new TransformationFailedException(sprintf(
                'Clef privé incorrec',
                $api_key
            ));
        }

        return $seller;
    }
} 