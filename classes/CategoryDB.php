<?php


class CategoryDB
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
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

    public function getIdCategory($desc)
    {
        $sql = $this->conn->prepare("SELECT idCategory FROM category WHERE description = :desc");
        $sql->bindParam(':desc', $desc);
        $sql->execute();
        $item = $sql->fetch(PDO::FETCH_ASSOC);
        return $item;
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

    public function deleteCategory($id)
    {
        $sql = $this->conn->prepare("DELETE FROM category WHERE idCategory=:id");
        $sql->bindParam(':id', $id);
        $sql->execute();
    }


    public function updateCategory($id, $name, $desc, $idParent){
        $stmt = $this->conn->prepare("UPDATE category SET categoryName=:categoryName, description=:description, Category_idCategory=:idParent WHERE idCategory=:id");
        $stmt->execute(['categoryName' => $name, 'description' => $desc, 'idParent' => $idParent, 'id' => $id,]);
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getAllCategory(){
        $stmt = $this->conn->prepare("SELECT * FROM category");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

}