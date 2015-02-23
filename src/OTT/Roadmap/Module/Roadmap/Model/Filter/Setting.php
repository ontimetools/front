<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Filter;
/**
 * Class Client
 * @package OTT\Roadmap\Module\Roadmap\Model\Filter
 */
class Setting
{
    /** @var int */
    protected $id = null;
    /** @var int */
    protected $parent_id = null;
    /** @var int */
    protected $client_id = null;
    /** @var bool */
    protected $active = true;
    /** @var bool */
    protected $merged_with_parent = true;

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
     * @return boolean
     */
    public function isMergedWithParent()
    {
        return $this->merged_with_parent;
    }

    /**
     * @param boolean $merged_with_parent
     */
    public function setMergedWithParent($merged_with_parent)
    {
        $this->merged_with_parent = $merged_with_parent;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }
}