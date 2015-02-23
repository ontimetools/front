<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class Estimate
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class Estimate extends EntityAbstract
{
    /** @var float */
    protected $amount;
    /** @var string */
    protected $unit;
    /** @var string */
    protected $formated;

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return string
     */
    public function getFormated()
    {
        return $this->formated;
    }

    /**
     * @param string $formated
     */
    public function setFormated($formated)
    {
        $this->formated = $formated;
    }
}