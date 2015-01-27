<?php


namespace Ferus\EventBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ArrayTransformer implements DataTransformerInterface
{
    /**
     * @param array $array
     *
     * @return string
     */
    public function transform($array)
    {
        if($array == null)
            return '';
        return implode('|', $array);
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function reverseTransform($string)
    {
        if($string == null)
            return array();
        return explode('|', $string);
    }

} 