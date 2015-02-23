<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class Client extends EntityAbstract
{
    /** @var int */
    protected $id;
    /** @var float */
    protected $hour_per_day;
    /** @var float */
    protected $day_per_week;
    /** @var float */
    protected $week_per_iteration;
    /** @var \DateTime() */
    protected $date_create;
    /** @var \DateTime() */
    protected $date_update;
    /** @var \DateTime() */
    protected $date_delete;
    /** @var string */
    protected $subdomain;
    /** @var string */
    protected $ot_url;
    /** @var string */
    protected $ot_client_id;
    /** @var string */
    protected $ot_client_secret;

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
     * @return float
     */
    public function getDayPerWeek()
    {
        return $this->day_per_week;
    }

    /**
     * @param float $day_per_week
     */
    public function setDayPerWeek($day_per_week)
    {
        $this->day_per_week = $day_per_week;
    }

    /**
     * @return float
     */
    public function getHourPerDay()
    {
        return $this->hour_per_day;
    }

    /**
     * @param float $hour_per_day
     */
    public function setHourPerDay($hour_per_day)
    {
        $this->hour_per_day = $hour_per_day;
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
     * @return string
     */
    public function getOtClientId()
    {
        return $this->ot_client_id;
    }

    /**
     * @param string $ot_client_id
     */
    public function setOtClientId($ot_client_id)
    {
        $this->ot_client_id = $ot_client_id;
    }

    /**
     * @return string
     */
    public function getOtClientSecret()
    {
        return $this->ot_client_secret;
    }

    /**
     * @param string $ot_client_secret
     */
    public function setOtClientSecret($ot_client_secret)
    {
        $this->ot_client_secret = $ot_client_secret;
    }

    /**
     * @return string
     */
    public function getOtUrl()
    {
        return $this->ot_url;
    }

    /**
     * @param string $ot_url
     */
    public function setOtUrl($ot_url)
    {
        $this->ot_url = $ot_url;
    }

    /**
     * @return string
     */
    public function getSubdomain()
    {
        return $this->subdomain;
    }

    /**
     * @param string $subdomain
     */
    public function setSubdomain($subdomain)
    {
        $this->subdomain = $subdomain;
    }

    /**
     * @return float
     */
    public function getWeekPerIteration()
    {
        return $this->week_per_iteration;
    }

    /**
     * @param float $week_per_iteration
     */
    public function setWeekPerIteration($week_per_iteration)
    {
        $this->week_per_iteration = $week_per_iteration;
    }
}