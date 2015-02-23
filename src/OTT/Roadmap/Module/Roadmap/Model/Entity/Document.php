<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class Document
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class Document extends EntityAbstract
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $client_id;
    /** @var int */
    protected $setting_id;
    /** @var string */
    protected $name;
    /** @var string */
    protected $keywords;
    /** @var \DateTime */
    protected $date_begin;
    /** @var \DateTime */
    protected $date_end;
    /** @var \DateTime */
    protected $date_create;
    /** @var \DateTime */
    protected $date_update;
    /** @var \DateTime */
    protected $date_delete;
    /** @var bool */
    protected $private;
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
    /** @var DocumentEntry[] */
    protected $entries;

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
        $this->client_id = intval($client_id);
    }

    /**
     * @return \DateTime
     */
    public function getDateBegin()
    {
        return $this->date_begin;
    }

    /**
     * @param \DateTime $date_begin
     */
    public function setDateBegin($date_begin)
    {
        $this->date_begin = $this->setDate($date_begin);
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
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * @param \DateTime $date_end
     */
    public function setDateEnd($date_end)
    {
        $this->date_end = $this->setDate($date_end);
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
     * @return DocumentEntry[]
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @param DocumentEntry[] $entries
     */
    public function setEntries($entries)
    {
        $this->entries = $entries;
    }

    /**
     * @param DocumentEntry $entry
     */
    public function addEntry(DocumentEntry $entry)
    {
        $this->entries[] = $entry;
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
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param string $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
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
        $ot_project_id = $ot_project_id === "" ? null : intval($ot_project_id);
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
        $ot_release_id = $ot_release_id === "" ? null : intval($ot_release_id);
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
     * @return boolean
     */
    public function isPrivate()
    {
        return $this->private;
    }

    /**
     * @param boolean $private
     */
    public function setPrivate($private)
    {
        $this->private = (bool)$private;
    }

    /**
     * @return int
     */
    public function getSettingId()
    {
        return $this->setting_id;
    }

    /**
     * @param int $setting_id
     */
    public function setSettingId($setting_id)
    {
        $this->setting_id = intval($setting_id);
    }
}