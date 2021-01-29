<?php


class InvoiceDB
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
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

    public function  getInvoiceByOrderId($id){
        $stmt = $this->conn->prepare("SELECT * FROM invoice WHERE Order_idOrder=:id");
        $stmt->execute(['id'=>$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

}