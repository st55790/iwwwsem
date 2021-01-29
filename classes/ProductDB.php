<?php


class ProductDB
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
    }

    public function deleteProduct($id)
    {
        $sql = $this->conn->prepare("DELETE FROM product WHERE idProduct=:id");
        $sql->bindParam(':id', $id);
        $sql->execute();
    }

    public function getProductById($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct= :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function updateProduct($id, $name, $price, $desc, $vat, $filename)
    {
        $stmt = $this->conn->prepare("UPDATE product SET productName=:productName, price=:price, productDescription=:description, vat=:vat, imgLink=:img WHERE idProduct=:id");
        $stmt->execute(['productName' => $name, 'price' => $price, 'description' => $desc, 'vat' => $vat, 'img' => $filename, 'id' => $id,]);
    }

    public function insertProduct($name, $price, $desc, $vat, $filename)
    {
        $sql = $this->conn->prepare("INSERT INTO product (productName, price, productDescription, vat, imgLink) VALUES(:nameProduct, :price, :description, :vat, :imgLink)");
        $sql->execute(['nameProduct' => $name, 'price' => $price, 'description' => $desc, 'vat' => $vat, 'imgLink' => $filename]);
    }

    public function getProducts($description)
    {
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getProduct($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getAllProducts()
    {
        $sql = $this->conn->prepare("SELECT * FROM product");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCountProducts()
    {

        $sql = $this->conn->prepare("SELECT COUNT(idProduct) FROM product");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getProductsOfCategory()
    {
        $sql = $this->conn->prepare("SELECT * FROM category c, product p, product_has_category pc WHERE c.idCategory
            =pc.Category_idCategory AND p.idProduct = pc.Product_idProduct");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getProductByName($name){
        $sql = $this->conn->prepare("SELECT * FROM product WHERE productName = :name");
        $sql->execute(['name'=>$name]);
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

}