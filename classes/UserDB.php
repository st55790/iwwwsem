<?php


class UserDB
{

    private $conn;

    public function __construct()
    {
        $this->conn = Connection::getPdoInstance();
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
        $sql = $this->conn->prepare("INSERT INTO user (firstName, lastName, email, password, privileges) VALUES (:firstName, :lastName, :email,:psw,:privil)");
        $sql->execute(['firstName'=>$firstName, 'lastName'=>$lastName, 'email'=>$email, 'psw'=>$password, 'privil'=>$privileges]);
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

    public function updateUser($id, $firstname, $lastname, $email, $password)
    {
        $sql = "UPDATE user SET firstName=:firstname, lastName=:lastname, email=:email, password=:psw WHERE idUser =:id";
        $data = ['firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'psw' => $password, 'id' => $id];
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
    }

}