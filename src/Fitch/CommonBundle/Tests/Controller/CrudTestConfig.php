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
    protected $formData;

    /** @var  array */
    protected $checkBoxes;

    /** @var  array */
    protected $fixedFormData;

    /** @var  array */
    protected $editFormData;

    /** @var  array */
    protected $badEditFormData;

    /** @var  callable */
    protected $checkAdditionFunction;

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
    public function getFormData()
    {
        return $this->formData;
    }

    /**
     * @param array $formData
     *
     * @return $this
     */
    public function setFormData($formData)
    {
        $this->formData = $formData;

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
    public function getFixedFormData()
    {
        return $this->fixedFormData;
    }

    /**
     * @param array $fixedFormData
     *
     * @return $this
     */
    public function setFixedFormData($fixedFormData)
    {
        $this->fixedFormData = $fixedFormData;

        return $this;
    }

    /**
     * @return array
     */
    public function getEditFormData()
    {
        return $this->editFormData;
    }

    /**
     * @param array $editFormData
     *
     * @return $this
     */
    public function setEditFormData($editFormData)
    {
        $this->editFormData = $editFormData;

        return $this;
    }

    /**
     * @return callable
     */
    public function getCheckAdditionFunction()
    {
        return $this->checkAdditionFunction;
    }

    /**
     * @param callable $checkAdditionFunction
     *
     * @return $this
     */
    public function setCheckAdditionFunction($checkAdditionFunction)
    {
        $this->checkAdditionFunction = $checkAdditionFunction;

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
