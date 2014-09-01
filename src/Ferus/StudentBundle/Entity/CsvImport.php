<?php


namespace Ferus\StudentBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class CsvImport 
{
    /**
     * @Assert\NotBlank()
     */
    private $csv;

    /**
     * @param mixed $csv
     */
    public function setCsv($csv)
    {
        $this->csv = $csv;
    }

    /**
     * @return mixed
     */
    public function getCsv()
    {
        return $this->csv;
    }
} 