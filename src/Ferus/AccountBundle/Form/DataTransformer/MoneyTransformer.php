<?php
namespace Ferus\AccountBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MoneyTransformer implements DataTransformerInterface
{
    /**
     * @param float $value
     * @return string
     */
    public function transform($value)
    {
        return number_format($value, 2, '.', ' ');
    }

    /**
     * @param string $value
     * @return float
     */
    public function reverseTransform($value)
    {
        $value = trim($value);
        $value = str_replace(',', '.', $value);
        $value = str_replace(' ', '', $value);

        if(!preg_match('/^-?(\d+(\.\d{2})?)?$/', $value)){
            throw new TransformationFailedException();
        }

        return floatval($value);
    }

} 