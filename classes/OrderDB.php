<?php


class OrderDB
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
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

    public function deleteFullOrder($id){
        $sql = $this->conn->prepare("DELETE FROM orders WHERE idOrder=:id");
        $sql->bindParam(':id', $id);
        $sql->execute();
    }
}