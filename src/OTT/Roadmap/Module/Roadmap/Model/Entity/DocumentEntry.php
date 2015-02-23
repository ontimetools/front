<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Entity;

/**
 * Class DocumentEntry
 * @package OTT\Roadmap\Module\Roadmap\Model\Entity
 */
class DocumentEntry extends EntityAbstract
{
    /** @var int */
    protected $id;
    /** @var int */
    protected $document_id;
    /** @var int (Parent Id) */
    protected $entry_id;
    /** @var int */
    protected $ot_id;
    /** @var string */
    protected $type;
    /** @var string */
    protected $status;
    /** @var string */
    protected $name;
    /** @var string */
    protected $project_name;
    /** @var string */
    protected $project_parent_name;
    /** @var string */
    protected $description_short;
    /** @var string */
    protected $description_complete;
    /** @var Estimate */
    protected $estimate;
    /** @var Estimate */
    protected $remaining;
    /** @var Estimate */
    protected $actual;
    /** @var string */
    protected $estimate_unit;
    /** @var Estimate */
    protected $estimate_management;
    /** @var Estimate */
    protected $remaining_management;
    /** @var Estimate */
    protected $actual_management;
    /** @var float */
    protected $percent_complete = null;
    /** @var string */
    protected $priority_name;
    /** @var \DateTime */
    protected $date_create;
    /** @var \DateTime */
    protected $date_update;
    /** @var \DateTime */
    protected $date_delete;
    /** @var Price */
    protected $estimate_men_price;
    /** @var Price */
    protected $estimate_management_price;
    /** @var Price */
    protected $remaining_men_price;
    /** @var Price */
    protected $remaining_management_price;
    /** @var Price */
    protected $actual_men_price;
    /** @var Price */
    protected $actual_management_price;
    /** @var Price */
    protected $estimate_total_price;
    /** @var Price */
    protected $remaining_total_price;
    /** @var Price */
    protected $actual_total_price;

    /**
     * @return string
     */
    public function getEstimateUnit()
    {
        return $this->estimate_unit;
    }

    /**
     * @param string $estimate_unit
     */
    public function setEstimateUnit($estimate_unit)
    {
        $this->estimate_unit = $estimate_unit;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return Estimate
     */
    public function getActualManagement()
    {
        return $this->actual_management;
    }

    /**
     * @param Estimate $actual_management
     */
    public function setActualManagement($actual_management)
    {
        $this->actual_management = $actual_management;
    }

    /**
     * @return Price
     */
    public function getActualManagementPrice()
    {
        return $this->actual_management_price;
    }

    /**
     * @param Price $actual_management_price
     */
    public function setActualManagementPrice($actual_management_price)
    {
        $this->actual_management_price = $actual_management_price;
    }

    /**
     * @return Price
     */
    public function getActualMenPrice()
    {
        return $this->actual_men_price;
    }

    /**
     * @param Price $actual_men_price
     */
    public function setActualMenPrice($actual_men_price)
    {
        $this->actual_men_price = $actual_men_price;
    }

    /**
     * @return Price
     */
    public function getActualTotalPrice()
    {
        return $this->actual_total_price;
    }

    /**
     * @param Price $actual_total_price
     */
    public function setActualTotalPrice($actual_total_price)
    {
        $this->actual_total_price = $actual_total_price;
    }

    /**
     * @return Estimate
     */
    public function getEstimateManagement()
    {
        return $this->estimate_management;
    }

    /**
     * @param Estimate $estimate_management
     */
    public function setEstimateManagement($estimate_management)
    {
        $this->estimate_management = $estimate_management;
    }

    /**
     * @return Price
     */
    public function getEstimateManagementPrice()
    {
        return $this->estimate_management_price;
    }

    /**
     * @param Price $estimate_management_price
     */
    public function setEstimateManagementPrice($estimate_management_price)
    {
        $this->estimate_management_price = $estimate_management_price;
    }

    /**
     * @return Price
     */
    public function getEstimateMenPrice()
    {
        return $this->estimate_men_price;
    }

    /**
     * @param Price $estimate_men_price
     */
    public function setEstimateMenPrice($estimate_men_price)
    {
        $this->estimate_men_price = $estimate_men_price;
    }

    /**
     * @return Price
     */
    public function getEstimateTotalPrice()
    {
        return $this->estimate_total_price;
    }

    /**
     * @param Price $estimate_total_price
     */
    public function setEstimateTotalPrice($estimate_total_price)
    {
        $this->estimate_total_price = $estimate_total_price;
    }

    /**
     * @return Estimate
     */
    public function getRemainingManagement()
    {
        return $this->remaining_management;
    }

    /**
     * @param Estimate $remaining_management
     */
    public function setRemainingManagement($remaining_management)
    {
        $this->remaining_management = $remaining_management;
    }

    /**
     * @return Price
     */
    public function getRemainingManagementPrice()
    {
        return $this->remaining_management_price;
    }

    /**
     * @param Price $remaining_management_price
     */
    public function setRemainingManagementPrice($remaining_management_price)
    {
        $this->remaining_management_price = $remaining_management_price;
    }

    /**
     * @return Price
     */
    public function getRemainingMenPrice()
    {
        return $this->remaining_men_price;
    }

    /**
     * @param Price $remaining_men_price
     */
    public function setRemainingMenPrice($remaining_men_price)
    {
        $this->remaining_men_price = $remaining_men_price;
    }

    /**
     * @return Price
     */
    public function getRemainingTotalPrice()
    {
        return $this->remaining_total_price;
    }

    /**
     * @param Price $remaining_total_price
     */
    public function setRemainingTotalPrice($remaining_total_price)
    {
        $this->remaining_total_price = $remaining_total_price;
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
     * @return string
     */
    public function getDescriptionComplete()
    {
        return $this->description_complete;
    }

    /**
     * @param string $description_complete
     */
    public function setDescriptionComplete($description_complete)
    {
        $this->description_complete = $description_complete;
    }

    /**
     * @return string
     */
    public function getDescriptionShort()
    {
        return $this->description_short;
    }

    /**
     * @param string $description_short
     */
    public function setDescriptionShort($description_short)
    {
        $this->description_short = $description_short;
    }

    /**
     * @return int
     */
    public function getDocumentId()
    {
        return $this->document_id;
    }

    /**
     * @param int $document_id
     */
    public function setDocumentId($document_id)
    {
        $this->document_id = null == $document_id ? null : intval($document_id);
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
    public function setEntryId($entry_id = null)
    {
        $this->entry_id = null == $entry_id ? null : intval($entry_id);
    }

    /**
     * @return Estimate
     */
    public function getEstimate()
    {
        return $this->estimate;
    }

    /**
     * @param Estimate $estimate
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;
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
    public function getOtId()
    {
        return $this->ot_id;
    }

    /**
     * @param int $ot_id
     */
    public function setOtId($ot_id)
    {
        $this->ot_id = $ot_id;
    }

    /**
     * @return float
     */
    public function getPercentComplete()
    {
        return $this->percent_complete;
    }

    /**
     * @param float $percent_complete
     */
    public function setPercentComplete($percent_complete)
    {
        $this->percent_complete = $percent_complete;
    }

    /**
     * @return string
     */
    public function getPriorityName()
    {
        return $this->priority_name;
    }

    /**
     * @param string $priority_name
     */
    public function setPriorityName($priority_name)
    {
        $this->priority_name = $priority_name;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->project_name;
    }

    /**
     * @param string $project_name
     */
    public function setProjectName($project_name)
    {
        $this->project_name = $project_name;
    }

    /**
     * @return string
     */
    public function getProjectParentName()
    {
        return $this->project_parent_name;
    }

    /**
     * @param string $project_parent_name
     */
    public function setProjectParentName($project_parent_name)
    {
        $this->project_parent_name = $project_parent_name;
    }

    /**
     * @return Estimate
     */
    public function getActual()
    {
        return $this->actual;
    }

    /**
     * @param Estimate $actual
     */
    public function setActual($actual)
    {
        $this->actual = $actual;
    }

    /**
     * @param Estimate $remaining
     */
    public function setRemaining($remaining)
    {
        $this->remaining = $remaining;
    }

    /**
     * @return Estimate
     */
    public function getRemaining()
    {
        return $this->remaining;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}