<?php
//require_once "./classes/Connection.php";

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

    public function userExist($email)
    {
        $sql = "SELECT COUNT(email) AS num FROM user WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['num'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertUser($firstName, $lastName, $email, $password, $privileges)
    {
        $sql = "INSERT INTO user (firstName, lastName, email, password, privileges) VALUES ('$firstName', '$lastName', '$email','$password','$privileges')";
        //$stmt= $this->conn->prepare($sql);
        //$stmt->bindParam(':fistName', $firstName, ':lastName', $lastName, ':email', $email, ':password', $password, ':privileges', $privileges);
        //$stmt->exec($sql);
        $this->conn->exec($sql);
    }

    public function deleteUser($id)
    {
        $sql = $this->conn->prepare("DELETE FROM user WHERE idUser=:id");
        $sql->bindParam(':id', $id);
        $sql->execute();
    }

    public function getUser($email)
    {
        $sql = "SELECT * FROM user WHERE email= :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM user WHERE idUser= :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
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

    public function getCategories($parent_id)
    {
        if (is_null($parent_id)) {
            $sql = $this->conn->prepare("SELECT * FROM category WHERE Category_idCategory IS NULL");
        } else {
            $sql = $this->conn->prepare("SELECT * FROM category WHERE Category_idCategory = :category");
            $sql->bindParam(':category', $parent_id);
        }
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
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
        //$sql->bindParam(':table', $table);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function updateUser($id, $firstname, $lastname, $email, $password)
    {
        $sql = "UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, password=:psw WHERE idUser =:id";
        $data = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'psw' => $password, 'id' => $id];
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
    }

    public function getProducts($description)
    {
        $sql = $this->conn->prepare("SELECT * FROM product WHERE idProduct = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getIdCategory($desc)
    {
        $sql = $this->conn->prepare("SELECT idCategory FROM category WHERE description = :desc");
        $sql->bindParam(':desc', $desc);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
    }

    public function getProductsOfCategory()
    {
        $sql = $this->conn->prepare("SELECT * FROM category c, product p, product_has_category pc WHERE c.idCategory
            =pc.Category_idCategory AND p.idProduct = pc.Product_idProduct");
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;
    }

    public function insertOrder($id, $time)
    {
        $sql = $this->conn->prepare("INSERT INTO orders (User_idUser, timeOrder) VALUES (:id, :timeOrder)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':timeOrder', $time);
        $sql->execute();
    }

    public function getOrder($idUser, $time)
    {
        $sql = $this->conn->prepare("SELECT * FROM orders WHERE User_idUser=:idUser AND timeOrder=:timeOrd");
        $sql->execute(['idUser' => $idUser, 'timeOrd' => $time]);
        $order = $sql->fetch();
        return $order;
    }

    public function insertInvoice($mob, $city, $zip, $orderId)
    {
        $sql = $this->conn->prepare("INSERT INTO invoice (phone, city, postCode, Order_idOrder) VALUES(:phone, :city, :postCode, :idOrder)");
        $sql->bindParam(':phone', $mob);
        $sql->bindParam(':city', $city);
        $sql->bindParam(':postCode', $zip);
        $sql->bindParam(':idOrder', $orderId);
        $sql->execute();
    }

    public function insertOrderHasProduct($idOrder, $idProduct, $price, $quantity)
    {
        $sql = $this->conn->prepare("INSERT INTO order_has_product (Order_idOrder, Product_idProduct, price, quantity) VALUES(:idOrder, :idProduct, :price, :quantity)");
        $sql->execute(['idOrder' => $idOrder, 'idProduct' => $idProduct, 'price' => $price, 'quantity' => $quantity]);
    }

    public function orderExist($id)
    {
        $sql = "SELECT COUNT(idOrder) AS num FROM orders WHERE User_idUser = :idUser";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idUser', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['num'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserOrders($id)
    {
        $sql = $this->conn->prepare("SELECT * FROM orders WHERE User_idUser=:idUser");
        $sql->bindParam(':idUser', $id);
        $sql->execute();
        $result = $sql->fetchAll();
        return $result;

    }

    public function getCategoryById($id)
    {
        $sql = "SELECT * FROM category WHERE idCategory= :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function categoryExist($categoryName)
    {
        $sql = "SELECT COUNT(idCategory) AS num FROM category WHERE categoryName = :name";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':name', $categoryName);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['num'] > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insertCategory($name, $desc, $idParent)
    {
        $sql = $this->conn->prepare("INSERT INTO category (categoryName, description, Category_idCategory) VALUES(:categoryName, :description, :idParent)");
        $sql->execute(['categoryName' => $name, 'description' => $desc, 'idParent' => $idParent]);
    }

    public function insertProduct($name, $price, $desc, $vat, $filename)
    {
        $sql = $this->conn->prepare("INSERT INTO product (productName, price, productDescription, vat, imgLink) VALUES(:nameProduct, :price, :description, :vat, :imgLink)");
        $sql->execute(['nameProduct' => $name, 'price' => $price, 'description' => $desc, 'vat' => $vat, 'imgLink' => $filename]);
    }

    public function deleteCategory($id)
    {
        $sql = $this->conn->prepare("DELETE FROM category WHERE idCategory=:id");
        $sql->bindParam(':id', $id);
        $sql->execute();
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

    public function updateCategory($id, $name, $desc, $idParent){
        $stmt = $this->conn->prepare("UPDATE category SET categoryName=:categoryName, description=:description, Category_idCategory=:idParent WHERE idCategory=:id");
        $stmt->execute(['categoryName' => $name, 'description' => $desc, 'idParent' => $idParent, 'id' => $id,]);
    }
}