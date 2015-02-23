<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class Setting
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class Setting extends EntityAbstract
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $client_id;
    /** @var int */
    protected $parent_setting_id = null;
    /** @var string */
    protected $name;
    /** @var float */
    protected $project_security_rate = null;
    /** @var string */
    protected $project_currency = '&euro;';
    /** @var float */
    protected $men_day_price = null;
    /** @var float */
    protected $men_availability_rate = null;
    /** @var float */
    protected $men_availability_absolute = null;
    /** @var float */
    protected $management_day_price = null;
    /** @var float */
    protected $management_availability_rate = null;
    /** @var float */
    protected $management_availability_absolute = null;
    /** @var bool */
    protected $display_men_price = null;
    /** @var bool */
    protected $display_management_price = null;
    /** @var bool */
    protected $display_dates = null;
    /** @var int */
    protected $updater_id = null;
    /** @var \DateTime */
    protected $date_create = null;
    /** @var \DateTime */
    protected $date_update = null;
    /** @var \DateTime */
    protected $date_delete = null;

    /**
     * @return int
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @param \DateTime $date_create
     */
    public function setDateCreate($date_create)
    {
        $this->date_create = $this->setDate($date_create);
    }

    /**
     * @return \DateTime
     */
    public function getDateDelete()
    {
        return $this->date_delete;
    }

    /**
     * @param \DateTime $date_delete
     */
    public function setDateDelete($date_delete)
    {
        $this->date_delete = $this->setDate($date_delete);
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @param \DateTime $date_update
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $this->setDate($date_update);
    }

    /**
     * @return boolean
     */
    public function isDisplayDates()
    {
        return $this->display_dates;
    }

    /**
     * @param boolean $display_dates
     */
    public function setDisplayDates($display_dates)
    {
        $this->display_dates = $display_dates;
    }

    /**
     * @return boolean
     */
    public function isDisplayManagementPrice()
    {
        return $this->display_management_price;
    }

    /**
     * @param boolean $display_management_price
     */
    public function setDisplayManagementPrice($display_management_price)
    {
        $this->display_management_price = $display_management_price;
    }

    /**
     * @return boolean
     */
    public function isDisplayMenPrice()
    {
        return $this->display_men_price;
    }

    /**
     * @param boolean $display_men_price
     */
    public function setDisplayMenPrice($display_men_price)
    {
        $this->display_men_price = $display_men_price;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getManagementAvailabilityAbsolute()
    {
        return $this->management_availability_absolute;
    }

    /**
     * @param float $management_availability_absolute
     */
    public function setManagementAvailabilityAbsolute($management_availability_absolute)
    {
        $this->management_availability_absolute = $management_availability_absolute;
    }

    /**
     * @return float
     */
    public function getManagementAvailabilityRate()
    {
        return $this->management_availability_rate;
    }

    /**
     * @param float $management_availability_rate
     */
    public function setManagementAvailabilityRate($management_availability_rate)
    {
        $this->management_availability_rate = $management_availability_rate;
    }

    /**
     * @return float
     */
    public function getManagementDayPrice()
    {
        return floatval($this->management_day_price);
    }

    /**
     * @param float $management_day_price
     */
    public function setManagementDayPrice($management_day_price)
    {
        $this->management_day_price = $management_day_price;
    }

    /**
     * @return float
     */
    public function getMenAvailabilityAbsolute()
    {
        return $this->men_availability_absolute;
    }

    /**
     * @param float $men_availability_absolute
     */
    public function setMenAvailabilityAbsolute($men_availability_absolute)
    {
        $this->men_availability_absolute = $men_availability_absolute;
    }

    /**
     * @return float
     */
    public function getMenAvailabilityRate()
    {
        return $this->men_availability_rate;
    }

    /**
     * @param float $men_availability_rate
     */
    public function setMenAvailabilityRate($men_availability_rate)
    {
        $this->men_availability_rate = $men_availability_rate;
    }

    /**
     * @return float
     */
    public function getMenDayPrice()
    {
        return floatval($this->men_day_price);
    }

    /**
     * @param float $men_day_price
     */
    public function setMenDayPrice($men_day_price)
    {
        $this->men_day_price = $men_day_price;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getParentSettingId()
    {
        return $this->parent_setting_id;
    }

    /**
     * @param int $parent_setting_id
     */
    public function setParentSettingId($parent_setting_id)
    {
        $this->parent_setting_id = $parent_setting_id;
    }

    /**
     * @return string
     */
    public function getProjectCurrency()
    {
        return $this->project_currency;
    }

    /**
     * @param string $project_currency
     */
    public function setProjectCurrency($project_currency)
    {
        $this->project_currency = $project_currency;
    }

    /**
     * @return float
     */
    public function getProjectSecurityRate()
    {
        return $this->project_security_rate;
    }

    /**
     * @param float $project_security_rate
     */
    public function setProjectSecurityRate($project_security_rate)
    {
        $this->project_security_rate = $project_security_rate;
    }

    /**
     * @return int
     */
    public function getUpdaterId()
    {
        return $this->updater_id;
    }

    /**
     * @param int $updater_id
     */
    public function setUpdaterId($updater_id)
    {
        $this->updater_id = $updater_id;
    }
}