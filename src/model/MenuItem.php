<?php


class MenuItem
{
private $itemId, $title, $details, $isFood, $price, $withdrawn;

    public function __construct($itemId, $title, $details, $isFood, $price, $withdrawn)
    {
        $this->itemId = $itemId;
        $this->title = $title;
        $this->details = $details;
        $this->isFood = $isFood;
        $this->price = $price;
        $this->withdrawn = $withdrawn;

    }





    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param mixed $itemId
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * @param mixed $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return mixed
     */
    public function getIsFood()
    {
        return $this->isFood;
    }

    /**
     * @param mixed $isFood
     */
    public function setIsFood($isFood)
    {
        $this->isFood = $isFood;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getWithdrawn()
    {
        return $this->withdrawn;
    }

    /**
     * @param mixed $withdrawn
     */
    public function setWithdrawn($withdrawn)
    {
        $this->withdrawn = $withdrawn;
    }
}