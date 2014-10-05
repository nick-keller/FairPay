<?php
namespace Ferus\MailBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    /**
     * @param array $value
     * @return string
     */
    public function transform($value)
    {
        if($value === null)
            return '';

        return implode(', ', $value);
    }

    /**
     * @param string $value
     * @return float
     */
    public function reverseTransform($value)
    {
        $array = explode(',', $value);

        foreach($array as $k => $e)
            $array[$k] = strtolower(trim($e));

        return $array;
    }

} 