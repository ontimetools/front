<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Filter;
/**
 * Class Document
 * @package OTT\Roadmap\Module\Roadmap\Model\Filter
 */
class Document
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $entry_id;
    /** @var int */
    protected $user_id;
    /** @var int */
    protected $client_id;
    /** @var int */
    protected $ot_author_id;
    /** @var int */
    protected $ot_updater_id;
    /** @var int */
    protected $ot_project_id;
    /** @var int */
    protected $ot_release_id;
    /** @var int */
    protected $ot_customer_id;
    /** @var string */
    protected $ot_type;
    /** @var string */
    protected $ot_status;
    /** @var bool */
    protected $with_entries = false;
    /** @var bool */
    protected $with_settings = false;
    /** @var bool */
    protected $with_defects = false;
    /** @var bool */
    protected $with_features = false;
    /** @var bool */
    protected $with_tasks = false;
    /** @var bool */
    protected $with_incidents = false;
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
    public function getEntryId()
    {
        return $this->entry_id;
    }

    /**
     * @param int $entry_id
     */
    public function setEntryId($entry_id)
    {
        $this->entry_id = $entry_id;
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
     * @return int
     */
    public function getOtAuthorId()
    {
        return $this->ot_author_id;
    }

    /**
     * @param int $ot_author_id
     */
    public function setOtAuthorId($ot_author_id)
    {
        $this->ot_author_id = $ot_author_id;
    }

    /**
     * @return int
     */
    public function getOtCustomerId()
    {
        return $this->ot_customer_id;
    }

    /**
     * @param int $ot_customer_id
     */
    public function setOtCustomerId($ot_customer_id)
    {
        $this->ot_customer_id = $ot_customer_id;
    }

    /**
     * @return int
     */
    public function getOtProjectId()
    {
        return $this->ot_project_id;
    }

    /**
     * @param int $ot_project_id
     */
    public function setOtProjectId($ot_project_id)
    {
        $this->ot_project_id = $ot_project_id;
    }

    /**
     * @return int
     */
    public function getOtReleaseId()
    {
        return $this->ot_release_id;
    }

    /**
     * @param int $ot_release_id
     */
    public function setOtReleaseId($ot_release_id)
    {
        $this->ot_release_id = $ot_release_id;
    }

    /**
     * @return string
     */
    public function getOtStatus()
    {
        return $this->ot_status;
    }

    /**
     * @param string $ot_status
     */
    public function setOtStatus($ot_status)
    {
        $this->ot_status = $ot_status;
    }

    /**
     * @return string
     */
    public function getOtType()
    {
        return $this->ot_type;
    }

    /**
     * @param string $ot_type
     */
    public function setOtType($ot_type)
    {
        $this->ot_type = $ot_type;
    }

    /**
     * @return int
     */
    public function getOtUpdaterId()
    {
        return $this->ot_updater_id;
    }

    /**
     * @param int $ot_updater_id
     */
    public function setOtUpdaterId($ot_updater_id)
    {
        $this->ot_updater_id = $ot_updater_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return boolean
     */
    public function isWithDefects()
    {
        return $this->with_defects;
    }

    /**
     * @param boolean $with_defects
     */
    public function setWithDefects($with_defects)
    {
        $this->with_defects = $with_defects;
    }

    /**
     * @return boolean
     */
    public function isWithEntries()
    {
        return $this->with_entries;
    }

    /**
     * @param boolean $with_entries
     */
    public function setWithEntries($with_entries)
    {
        $this->with_entries = $with_entries;
    }

    /**
     * @return boolean
     */
    public function isWithFeatures()
    {
        return $this->with_features;
    }

    /**
     * @param boolean $with_features
     */
    public function setWithFeatures($with_features)
    {
        $this->with_features = $with_features;
    }

    /**
     * @return boolean
     */
    public function isWithIncidents()
    {
        return $this->with_incidents;
    }

    /**
     * @param boolean $with_incidents
     */
    public function setWithIncidents($with_incidents)
    {
        $this->with_incidents = $with_incidents;
    }

    /**
     * @return boolean
     */
    public function isWithSettings()
    {
        return $this->with_settings;
    }

    /**
     * @param boolean $with_settings
     */
    public function setWithSettings($with_settings)
    {
        $this->with_settings = $with_settings;
    }

    /**
     * @return boolean
     */
    public function isWithTasks()
    {
        return $this->with_tasks;
    }

    /**
     * @param boolean $with_tasks
     */
    public function setWithTasks($with_tasks)
    {
        $this->with_tasks = $with_tasks;
    }
}