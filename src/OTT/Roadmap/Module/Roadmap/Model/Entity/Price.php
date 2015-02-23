<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class Setting
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class Price extends EntityAbstract
{
    /** @var float */
    protected $amount;
    /** @var string */
    protected $currency;
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
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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