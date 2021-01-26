<?php


class Product
{
    private $id;
    private $name;
    private $price;
    private $vat;
    private $link;

    public function __construct($id, $name, $price, $vat, $link){
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->vat = $vat;
        $this->link = $link;
    }

    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getPrice(){
        return $this->price;
    }

    public function getVat(){
        return $this->vat;
    }

    public function getLink(){
        return $this->link;
    }
}