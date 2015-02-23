<?php

namespace OTT\Roadmap\Module\Roadmap\Model\Service\Message;

/**
 * Class MessageAbstract
 * @package OTT\Roadmap\Module\Roadmap\Model\Service\Message
 */
abstract class MessageAbstract
{
    /** @var bool */
    protected $success = false;
    /** @var array */
    protected $errors = [];
    /** @var mixed */
    protected $result;

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * @param boolean $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
} 