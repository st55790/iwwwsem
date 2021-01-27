<?php

class Product
{
    private $item;
    private $count;

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

    public function incrementCount(){
        $this->count +=1;
    }

    public function decrementCount(){
        $this->count -=1;
    }

    public function setCount($count){
        $this->count = $count;
    }
}
