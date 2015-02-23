<?php
namespace OTT\Roadmap\Module\Roadmap\Model\Helper;

use OTT\Processor\Entity\ItemAbstract;
use OTT\Processor\Helper\Item;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Client as ClientEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\DocumentEntry as DocumentEntryEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Document as DocumentEntity;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Estimate;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Price;
use OTT\Roadmap\Module\Roadmap\Model\Filter\Document as DocumentFilter;
use OTT\Roadmap\Module\Roadmap\Model\Entity\Setting as SettingEntity;

/**
 * Class Document
 * @package OTT\Roadmap\Module\Roadmap\Model\Helper
 */
class Document extends HelperAbstract
{
    const ESTIMUNIT_HRS = 'hrs';
    const ESTIMUNIT_DAYS = 'days';
    const ESTIMUNIT_SP = 'sp';
    const ESTIMUNIT_MIN = 'min';

    /**
     * @param array $datas
     * @param null $element
     * @return null|DocumentEntity
     */
    public static function fromDalToEntitySingle($datas = [], &$element = null)
    {
        $entity = null;
        if (
            null !== self::get($datas, 'id') &&
            null !== self::get($datas, 'client_id')
        ) {
            $entity = null === $element ? new DocumentEntity() : $element;
            $entity->setId(self::get($datas, 'id'));
            $entity->setClientId(self::get($datas, 'client_id'));
            $entity->setSettingId(self::get($datas, 'setting_id'));
            $entity->setName(self::get($datas, 'name'));
            $entity->setKeywords(self::get($datas, 'keywords'));
            $entity->setPrivate(self::get($datas, 'private'));
            $entity->setOtAuthorId(self::get($datas, 'ot_author_id'));
            $entity->setOtUpdaterId(self::get($datas, 'ot_updater_id'));
            $entity->setOtProjectId(self::get($datas, 'ot_project_id'));
            $entity->setOtReleaseId(self::get($datas, 'ot_release_id'));
            $entity->setOtCustomerId(self::get($datas, 'ot_customer_id'));
            $entity->setOtType(self::get($datas, 'ot_type'));
            $entity->setOtStatus(self::get($datas, 'ot_status'));
            $entity->setDateBegin(self::get($datas, 'date_begin'));
            $entity->setDateEnd(self::get($datas, 'date_end'));
            $entity->setDateCreate(self::get($datas, 'date_create'));
            $entity->setDateUpdate(self::get($datas, 'date_update'));
            $entity->setDateDelete(self::get($datas, 'date_delete'));
        }

        return $entity;
    }

    /**
     * @param array $datas
     * @param SettingEntity $settings
     * @param ClientEntity $client
     * @return array|null
     */
    public static function fromDalToEntityEntries(
        $datas = [],
        ClientEntity $client = null,
        SettingEntity $settings = null
    )
    {
        $result = null;
        if (is_array($datas)) {
            foreach ($datas as $data) {
                $result[] = self::fromDalToEntityEntrySingle($data, $client, $settings);
            }
        }

        return $result;
    }

    /**
     * @param array $datas
     * @param SettingEntity $settings
     * @param ClientEntity $client
     * @return null|DocumentEntryEntity
     */
    public static function fromDalToEntityEntrySingle(
        $datas = [],
        ClientEntity $client = null,
        SettingEntity $settings = null
    )
    {
        $entity = null;
        if (
            null !== self::get($datas, 'id') &&
            null !== self::get($datas, 'ot_id') &&
            null !== self::get($datas, 'document_id')
        ) {
            $entity = new DocumentEntryEntity();
            $entity->setId(self::get($datas, 'id'));
            $entity->setDocumentId(self::get($datas, 'document_id'));
            $entity->setEntryId(self::get($datas, 'entry_id'));
            $entity->setOtId(self::get($datas, 'ot_id'));
            $entity->setType(self::get($datas, 'type'));
            $entity->setName(self::get($datas, 'name'));
            $entity->setProjectName(self::get($datas, 'project_name'));
            $entity->setProjectParentName(self::get($datas, 'project_parent_name'));
            $entity->setDescriptionShort(self::get($datas, 'description_short'));
            $entity->setDescriptionComplete(self::get($datas, 'description_complete'));
            $entity->setPercentComplete(self::get($datas, 'percent_complete'));
            $entity->setPriorityName(self::get($datas, 'priority_name'));
            $entity->setDateCreate(self::get($datas, 'date_create'));
            $entity->setDateUpdate(self::get($datas, 'date_update'));
            $entity->setDateDelete(self::get($datas, 'date_delete'));
            $unit = self::get($datas, 'estimate_unit');
            $estimate = self::get($datas, 'estimate');
            $remaining = self::get($datas, 'remaining');
            $actual = self::get($datas, 'actual');
            $unit = $unit !== null ? $unit : self::ESTIMUNIT_DAYS;
            $managementMultiplier = 0;
            if (null !== $settings && null !== $client) {
                $toDays = self::getUnitConvertion($unit, $client);
                $securityRateMultiplier = 1 + ($settings->getProjectSecurityRate() / 100);
                $estimate = $estimate * $securityRateMultiplier * $toDays;
                $remaining = $remaining * $securityRateMultiplier * $toDays;
                $actual = $actual * $securityRateMultiplier * $toDays;
                if (null !== $settings->getManagementAvailabilityRate()) {
                    $managementMultiplier = ($settings->getManagementAvailabilityRate() / 100);
                }
                $entity->setEstimate(self::getEstimate($estimate, $unit));
                $entity->setRemaining(self::getEstimate($remaining, $unit));
                $entity->setActual(self::getEstimate($actual, $unit));
                $entity->setEstimateManagement(self::getEstimate(($estimate * $managementMultiplier), $unit));
                $entity->setRemainingManagement(self::getEstimate(($remaining * $managementMultiplier), $unit));
                $entity->setActualManagement(self::getEstimate(($actual * $managementMultiplier), $unit));
                $menDayPrice = $settings->getMenDayPrice();
                $managementDayPrice = $settings->getManagementDayPrice();
                $currency = $settings->getProjectCurrency();
                $entity->setEstimateMenPrice(self::getPrice($entity->getEstimate()->getAmount(), $menDayPrice, $currency));
                $entity->setEstimateManagementPrice(
                    self::getPrice($entity->getEstimateManagement()->getAmount(), $managementDayPrice, $currency)
                );
                $entity->setEstimateTotalPrice(
                    self::getTotalPrice($entity->getEstimateMenPrice(), $entity->getEstimateManagementPrice())
                );
                $entity->setRemainingMenPrice(self::getPrice($entity->getRemaining()->getAmount(), $menDayPrice, $currency));
                $entity->setRemainingManagementPrice(
                    self::getPrice($entity->getRemainingManagement()->getAmount(), $managementDayPrice, $currency)
                );
                $entity->setRemainingTotalPrice(
                    $entity->getRemainingMenPrice(), $entity->getRemainingManagementPrice()
                );
                $entity->setActualMenPrice(self::getPrice($entity->getActual()->getAmount(), $menDayPrice, $currency));
                $entity->setActualManagementPrice(
                    self::getPrice($entity->getActualManagement()->getAmount(), $managementDayPrice, $currency)
                );
                $entity->setActualTotalPrice($entity->getActualMenPrice(), $entity->getActualManagementPrice());
            } else {
                $entity->setEstimate(self::getEstimate($estimate, $unit));
                $entity->setRemaining(self::getEstimate($remaining, $unit));
                $entity->setActual(self::getEstimate($actual, $unit));
                $entity->setEstimateManagement(self::getEstimate(($estimate * $managementMultiplier), $unit));
                $entity->setRemainingManagement(self::getEstimate(($remaining * $managementMultiplier), $unit));
                $entity->setActualManagement(self::getEstimate(($actual * $managementMultiplier), $unit));
            }
            if ($remaining == 0) {
                if ($estimate >= $actual) {
                    $status = 'success';
                } else {
                    $status = 'warning';
                }
            } else {
                $status = 'default';
            }
            $entity->setStatus($status);
        }

        return $entity;
    }

    /**
     * @param $baseUnit
     * @param ClientEntity $client
     * @return float|int
     */
    private static function getUnitConvertion($baseUnit, ClientEntity $client)
    {
        switch ($baseUnit) {
            default:
            case self::ESTIMUNIT_DAYS:
            case self::ESTIMUNIT_SP:
                $toDays = 1;
                break;
            case self::ESTIMUNIT_HRS:
                $toDays = 1 / $client->getHourPerDay();
                break;
            case self::ESTIMUNIT_MIN:
                $toDays = 1 / (60 * $client->getHourPerDay());
                break;
        }

        return $toDays;
    }

    /**
     * @param $estimate
     * @param $unit
     * @return Estimate
     */
    private static function getEstimate($estimate, $unit)
    {
        $estimateEntity = new Estimate();
        if (is_float($estimate) || is_int($estimate)) {
            $estimateEntity->setAmount($estimate);
            $estimateEntity->setUnit(self::ESTIMUNIT_DAYS);
            $estimateEntity->setFormated(self::formatEstimate($estimateEntity));
//            $estimateEntity->setUnit($unit);
        }

        return $estimateEntity;
    }

    /**
     * @param Estimate $estimate
     * @return string
     */
    private static function formatEstimate(Estimate $estimate)
    {
        return round($estimate->getAmount(), 2) . '&nbsp;' . $estimate->getUnit();
    }

    /**
     * @param $estimate
     * @param $priceADay
     * @param string $currency
     * @return Price
     */
    private static function getPrice($estimate, $priceADay, $currency = '&euro;')
    {
        $price = new Price();
        if ($estimate instanceof Estimate) {
            $estimate = $estimate->getAmount();
        }
        if (is_float($priceADay)) {
            $price->setAmount($estimate * $priceADay);
            $price->setCurrency($currency);
            $price->setFormated(self::formatPrice($price));
        }

        return $price;
    }

    /**
     * @param Price $menPrice
     * @param Price $managementPrice
     * @return Price
     */
    private static function getTotalPrice(Price $menPrice, Price $managementPrice)
    {
        $price = new Price();
        $price->setAmount($menPrice->getAmount() + $managementPrice->getAmount());
        $price->setCurrency($menPrice->getCurrency());
        $price->setFormated(self::formatPrice($price));

        return $price;
    }

    /**
     * @param Price $price
     * @return Price
     */
    private static function formatPrice(Price $price)
    {
        $currency = $price->getCurrency();
        $amount = round($price->getAmount(), 0);
        if ($currency == '&pound;' || $currency == '$' || $currency == '£') {
            $result = $currency . $amount;
        } else {
            $result = str_replace('.', ',', $amount) . '&nbsp;' . $currency;
        }

        return $result;
    }

    /**
     * @param $datas
     * @param DocumentFilter $filter
     * @return array
     */
    public static function itemFromApiToEntityEntry($datas, DocumentFilter $filter)
    {
        $result = [];
        if (is_array($datas)) {
            foreach ($datas as $item) {
                if ($item instanceof ItemAbstract) {
                    $entity = self::itemFromApiToEntityEntrySingle($item, $filter);
                    if (null !== $entity) {
                        $result[] = $entity;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param ItemAbstract $item
     * @param DocumentFilter $filter
     * @return DocumentEntryEntity
     */
    protected static function itemFromApiToEntityEntrySingle(ItemAbstract $item, DocumentFilter $filter)
    {
        $entity = null;
        if (null !== $filter->getId()) {
            $entity = new DocumentEntryEntity();
            $entity->setDocumentId($filter->getId());
            $entity->setOtId($item->getId());
            $entity->setEntryId($item->getParent()->getId());
            $entity->setType($item->getItemType());
            $entity->setName($item->getName());
            $entity->setProjectName($item->getProject()->getName());
            if (null !== $item->getParentProject()) {
                $entity->setProjectParentName($item->getParentProject()->getName());
            }
            $entity->setDescriptionShort($item->getDescription());
            $entity->setDescriptionComplete($item->getNotes());
            $entity->setEstimate($item->getEstimatedDuration()->getValue());
            $entity->setRemaining($item->getRemainingDuration()->getValue());
            $entity->setActual($item->getActualDuration()->getValue());
            $entity->setEstimateUnit($item->getEstimatedDuration()->getTimeUnit());
            $entity->setPercentComplete($item->getPercentComplete());
            $entity->setPriorityName($item->getPriority()->getName());
        }

        return $entity;
    }
} 