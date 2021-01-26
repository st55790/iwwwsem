<?php
//require_once "./classes/Connection.php";

class Database
{
    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
    }

    public function userExist($email){
        $sql = "SELECT COUNT(email) AS num FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row['num'] > 0){
            return true;
        }else{
            return false;
        }
    }

    public function insertUser($firstName, $lastName, $email, $password, $privileges){
        $sql = "INSERT INTO user (firstName, lastName, email, password, privileges) VALUES ('$firstName', '$lastName', '$email','$password','$privileges')";
        //$stmt= $this->conn->prepare($sql);
        //$stmt->bindParam(':fistName', $firstName, ':lastName', $lastName, ':email', $email, ':password', $password, ':privileges', $privileges);
        //$stmt->exec($sql);
        $this->conn->exec($sql);
    }

    public function getUser($email){
        $sql = "SELECT * FROM user WHERE email= :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getProduct($id){
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getAllProducts(){
        $sql = $this->conn->prepare("SELECT * FROM product");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCountProducts(){

        $sql = $this->conn->prepare("SELECT COUNT(idProduct) FROM product");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getCategories($parent_id){
        if(is_null($parent_id)){
            $sql = $this->conn->prepare("SELECT * FROM category WHERE Category_idCategory IS NULL");
        }else{
            $sql = $this->conn->prepare("SELECT * FROM category WHERE Category_idCategory = :category");
            $sql->bindParam(':category', $parent_id);
        }
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function getAllFromTable($table){
        if($table == 'user'){
            $sql = $this->conn->prepare("SELECT * FROM user");
        }
        if($table == 'category'){
            $sql = $this->conn->prepare("SELECT * FROM category");
        }
        if($table == 'order'){
            //$sql = $this->conn->prepare("SELECT * FROM order");
        }
        if($table == 'product'){
            $sql = $this->conn->prepare("SELECT * FROM product");
        }
        //$sql->bindParam(':table', $table);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function updateUser($id, $firstname, $lastname, $email, $password){
        $sql = "UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, password=:psw WHERE idUser =:id";
        $data = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'psw' => $password, 'id' =>$id];
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
    }

    public function getProducts($description){
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getIdCategory($desc){
        $sql = $this->conn->prepare("SELECT idCategory FROM category WHERE description = :desc");
        $sql->bindParam(':desc', $desc);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getProductsOfCategory(){
        $sql = $this->conn->prepare("SELECT * FROM category c, product p, product_has_category pc WHERE c.idCategory
            =pc.Category_idCategory AND p.idProduct = pc.Product_idProduct");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

}