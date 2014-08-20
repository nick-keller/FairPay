<?php


namespace Ferus\TransactionBundle\Graph;


use Symfony\Component\Validator\Constraints\DateTime;

class GraphData
{
    /**
     * @var \DateTime
     */
    public $min;

    /**
     * @var \DateTime
     */
    private $max;

    private $incomes = null;

    private $outcomes = null;

    private $balance = null;

    function __construct()
    {
        $this->max = new \DateTime;
        $this->min = new \DateTime;
    }

    private function getDateTime($data)
    {
        return new \DateTime($data['year'].'-'.$data['month'].'-'.$data['day']);
    }

    private function getIndex($data)
    {
        return $this->min->diff($this->getDateTime($data))->d;
    }

    public function computeData($current_balance)
    {
        $length = $this->max->diff($this->min)->d;
        $empty = array_fill(0, $length +1, 0);

        $incomes = $this->incomes;
        $outcomes = $this->outcomes;

        $this->incomes = $empty;
        $this->outcomes = $empty;
        $this->balance = $empty;

        foreach($incomes as $income)
            $this->incomes[$this->getIndex($income)] = floatval($income[1]);

        foreach($outcomes as $outcome)
            $this->outcomes[$this->getIndex($outcome)] = floatval($outcome[1]);

        $this->balance[$length] = $current_balance;

        for($i = $length-1; $i >= 0; --$i)
            $this->balance[$i] = $this->balance[$i+1] + $this->outcomes[$i+1] - $this->incomes[$i+1];
    }

    /**
     * @param mixed $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return implode(',', $this->balance);
    }

    /**
     * @param mixed $incomes
     */
    public function setIncomes($incomes)
    {
        $this->incomes = $incomes;

        $max = $this->getDateTime($incomes[0]);
        $min = $this->getDateTime(end($incomes));

        $this->max = max($this->max, $max);
        $this->min = min($this->min, $min);
    }

    /**
     * @return mixed
     */
    public function getIncomes()
    {
        return implode(',', $this->incomes);
    }

    /**
     * @param mixed $outcomes
     */
    public function setOutcomes($outcomes)
    {
        $this->outcomes = $outcomes;

        $max = $this->getDateTime($outcomes[0]);
        $min = $this->getDateTime(end($outcomes));

        $this->max = max($this->max, $max);
        $this->min = min($this->min, $min);
    }

    /**
     * @return mixed
     */
    public function getOutcomes()
    {
        return implode(',', $this->outcomes);
    }
} 