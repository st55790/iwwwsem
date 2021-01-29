<?php

class Database
{
    private $conn;
    private $hash = "$2y$12$3ee/3yNboD6S2IhTTQVgNuf.qse1vNuTvzRjpbo0zQLs4JRqmCHr2";

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
    }

    public function getHash()
    {
        return $this->hash;
    }

    public function getAllFromTable($table)
    {
        if ($table == 'user') {
            $sql = $this->conn->prepare("SELECT * FROM user");
        }
        if ($table == 'category') {
            $sql = $this->conn->prepare("SELECT * FROM category");
        }
        if ($table == 'order') {
            //$sql = $this->conn->prepare("SELECT * FROM order");
        }
        if ($table == 'product') {
            $sql = $this->conn->prepare("SELECT * FROM product");
        }
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }



    public function insertOrderHasProduct($idOrder, $idProduct, $price, $quantity)
    {
        $sql = $this->conn->prepare("INSERT INTO order_has_product (Order_idOrder, Product_idProduct, price, quantity) VALUES(:idOrder, :idProduct, :price, :quantity)");
        $sql->execute(['idOrder' => $idOrder, 'idProduct' => $idProduct, 'price' => $price, 'quantity' => $quantity]);
    }

    public function insertProductHasCategory($idProduct, $idCategory){
        $sql = $this->conn->prepare("INSERT IGNORE INTO product_has_category (Product_idProduct, Category_idCategory) VALUES(:idProd, :idCat)");
        $sql->execute(['idProd'=>$idProduct, 'idCat'=>$idCategory]);
    }

    public function getAllProductFromOrderByIdOrder($id){
        $stmt = $this->conn->prepare("SELECT * FROM order_has_product WHERE Order_idOrder=:id");
        $stmt->execute(['id'=>$id]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getAllProductCategory($id){
        $stmt = $this->conn->prepare("SELECT * FROM product_has_category pc, category c WHERE pc.Category_idCategory = c.idCategory AND pc.Product_idProduct=:id");
        $stmt->execute(['id'=>$id]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function deleteProductHasCategory($id){
        $stmt = $this->conn->prepare("DELETE FROM product_has_category WHERE Product_idProduct=:id");
        $stmt->execute(['id'=>$id]);
    }
}