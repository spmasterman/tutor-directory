<?php

namespace Fitch\CommonBundle\Tests\Controller;

/**
 * Class CrudTestConfig.
 */
class CrudTestConfig
{
    /** @var  string */
    protected $user;

    /** @var  string */
    protected $url;

    /** @var  array */
    protected $badCreateFormData;

    /** @var  array */
    protected $checkBoxes;

    /** @var  array */
    protected $fixedCreateFormData;

    /** @var  array */
    protected $fixedEditFormData;

    /** @var  array */
    protected $badEditFormData;

    /** @var  callable */
    protected $checkCreateFunction;

    /** @var  callable */
    protected $checkEditFunction;

    /** @var  callable */
    protected $checkBadEditFunction;

    /** @var  callable */
    protected $checkDeletedFunction;

    /** @var  callable */
    protected $checkBadUpdateFunction;

    /** @var  bool */
    protected $includeUniqueChecks = true;

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function getBadCreateFormData()
    {
        return $this->badCreateFormData;
    }

    /**
     * @param array $badCreateFormData
     *
     * @return $this
     */
    public function setBadCreateFormData($badCreateFormData)
    {
        $this->badCreateFormData = $badCreateFormData;

        return $this;
    }

    /**
     * @return array
     */
    public function getCheckBoxes()
    {
        return $this->checkBoxes;
    }

    /**
     * @param array $checkBoxes
     *
     * @return $this
     */
    public function setCheckBoxes($checkBoxes)
    {
        $this->checkBoxes = $checkBoxes;

        return $this;
    }

    /**
     * @return array
     */
    public function getFixedCreateFormData()
    {
        return $this->fixedCreateFormData;
    }

    /**
     * @param array $fixedCreateFormData
     *
     * @return $this
     */
    public function setFixedCreateFormData($fixedCreateFormData)
    {
        $this->fixedCreateFormData = $fixedCreateFormData;

        return $this;
    }

    /**
     * @return array
     */
    public function getFixedEditFormData()
    {
        return $this->fixedEditFormData;
    }

    /**
     * @param array $fixedEditFormData
     *
     * @return $this
     */
    public function setFixedEditFormData($fixedEditFormData)
    {
        $this->fixedEditFormData = $fixedEditFormData;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckCreateFunction()
    {
        return $this->checkCreateFunction;
    }

    /**
     * @param callable $checkCreateFunction
     *
     * @return $this
     */
    public function setCheckCreateFunction($checkCreateFunction)
    {
        $this->checkCreateFunction = $checkCreateFunction;

        return $this;
    }

    /**
     * @return array
     */
    public function getBadEditFormData()
    {
        return $this->badEditFormData;
    }

    /**
     * @param array $badEditFormData
     *
     * @return $this
     */
    public function setBadEditFormData($badEditFormData)
    {
        $this->badEditFormData = $badEditFormData;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckEditFunction()
    {
        return $this->checkEditFunction;
    }

    /**
     * @param callable $checkEditFunction
     *
     * @return $this
     */
    public function setCheckEditFunction($checkEditFunction)
    {
        $this->checkEditFunction = $checkEditFunction;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckBadEditFunction()
    {
        return $this->checkBadEditFunction;
    }

    /**
     * @param callable $checkBadEditFunction
     *
     * @return $this
     */
    public function setCheckBadEditFunction($checkBadEditFunction)
    {
        $this->checkBadEditFunction = $checkBadEditFunction;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckDeletedFunction()
    {
        return $this->checkDeletedFunction;
    }

    /**
     * @param callable $checkDeletedFunction
     *
     * @return $this
     */
    public function setCheckDeletedFunction($checkDeletedFunction)
    {
        $this->checkDeletedFunction = $checkDeletedFunction;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckBadUpdateFunction()
    {
        return $this->checkBadUpdateFunction;
    }

    /**
     * @param callable $checkBadUpdateFunction
     *
     * @return $this
     */
    public function setCheckBadUpdateFunction($checkBadUpdateFunction)
    {
        $this->checkBadUpdateFunction = $checkBadUpdateFunction;

        return $this;
    }

    /**
     * @return boolean
     */
    public function areUniqueChecksEnabled()
    {
        return $this->includeUniqueChecks;
    }

    /**
     * @return $this
     */
    public function disableUniqueChecks()
    {
        $this->includeUniqueChecks = false;

        return $this;
    }
}
