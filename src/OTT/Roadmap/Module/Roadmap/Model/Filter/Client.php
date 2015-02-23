<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Filter;
/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Filter
 */
class Client
{
    /** @var int */
    protected $id = null;
    /** @var string */
    protected $subdomain = null;
    /** @var bool */
    protected $active = true;

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
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
}