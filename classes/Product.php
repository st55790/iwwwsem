<?php


class Product
{
    public $item;
    public $count;

    public function __construct($item, $count)
    {
        $this->item = $item;
        $this->count = $count;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setCount($count){
        $this->count = $count;
    }
}