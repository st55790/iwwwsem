<?php
$db = new Database();

if(isset($_POST['submit'])){
    $name = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : null);
    $last_name = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : null);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $privileges = htmlspecialchars(!empty($_POST['privileges']) ? trim($_POST['privileges']) : null);

    if($db->userExist($email)){
        echo("<p class='alert'>Uzivatel jiz existuje</p>");
    }else{
        $hashpsw = password_hash($pass, PASSWORD_BCRYPT);
        $db->insertUser($name, $last_name, $email, $hashpsw, $privileges);
        header('Location:/admin.php?table=user');
    }
}
?>


<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>


    <form action="">
        <input type="submit" name="table" value="user">
        <input type="submit" name="table" value="order">
        <input type="submit" name="table" value="invoice">
        <input type="submit" name="table" value="category">
        <input type="submit" name="table" value="product">
    </form>


<?php

if(isset($_GET['table'])){
    if($_GET['table'] == 'user'){
    echo '<form method="post" action="">
                <label>Jmeno</label>
                <input type="text" name="fname" required><br>
                <label>Prijmeni</label>
                <input type="text" name="lname" required><br>
                <label>Email</label>
                <input type="email" name="email" required><br>
                <label>Heslo</label>
                <input type="password" name="psw" required><br>
                <label>Prava</label>
                <select name="privileges">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select><br>
                <input type="submit"Přidej name="submit" value="Add">
           </form><br>';
    }
}

if (isset($_GET['table'])) {
    if($_GET['table'] == 'user'){
        if (isset($_GET['edit'])) {
            echo 'EDIT USER';

            $user = $db->getUserById($_GET['edit']);

            echo '<form action="">
                <label>Jmeno</label>
                <input type="text" name="fname" value="'.$user['firstName'].'" required><br>
                <label>Prijmeni</label>
                <input type="text" name="lname" value="'.$user['lastName'].'"required><br>
                <label>Email</label>
                <input type="email" name="email" value="'.$user['email'].'"required><br>
                <label>Heslo</label>
                <input type="text" name="psw" value="'.$user['password'].'"required><br>
                <label>Prava</label>
                <select name="privileges">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select><br>
                <input type="submit"Přidej name="editSubmit" value="Edit">
           </form><br>';

        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $db->deleteUser($id);
        }
    }
}

echo '<div class="adminTables">';

if(isset($_GET['table'])){
    $tab = $_GET['table'];
    $tableData = $db->getAllFromTable($tab);
    if($tab == 'user'){
        //echo 'USERS';
            echo '<table>
                    <thred>
                        <tr>
                            <th>ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Privileges</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thred>';
        foreach ($tableData as $data){
            echo '<tr>
                    <td>'.$data['idUser'].'</td>
                    <td>'. $data['firstName'].'</td>
                    <td>'. $data['lastName'].'</td>
                    <td>'. $data['email'].'</td>
                    <td>'. $data['password'].'</td>
                    <td>'. $data['privileges'].'</td>
                    <td>
                        <a href="admin.php?&table=user&edit='.$data['idUser'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=user&remove='.$data['idUser'].'" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            }
        echo '</table>';
    }
    if($tab == 'product'){
        echo '<table>
                    <thred>
                        <tr>
                            <th>ID</th>
                            <th>Product name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>VAT</th>
                            <th>Img link</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thred>';
        foreach ($tableData as $data){
            echo '<tr>
                    <td>'. $data['idProduct'].'</td>
                    <td>'. $data['productName'].'</td>
                    <td>'. $data['price'].'</td>
                    <td>'. $data['productDescription'].'</td> <!-- .substr($data[\'productDescription\'], 0,100).... -->
                    <td>'. $data['vat'].'</td>
                    <td>'. $data['imgLink'].'</td>
                    <td>
                        <a href="admin.php?&table=product&edit='.$data['idProduct'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=product&remove='.$data['idProduct'].'" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            //print_r($data);
            //echo '<br>';
        }
        echo '</table>';
    }
    if($tab == 'category'){
        echo '<table>
                    <thred>
                        <tr>
                            <th>ID</th>
                            <th>Category name</th>
                            <th>Description</th>
                            <th>ID parent</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thred>';
        foreach ($tableData as $data){
            echo '<tr>
                    <td>'. $data['idCategory'].'</td>
                    <td>'. $data['categoryName'].'</td>
                    <td>'. $data['description'].'"</td>
                    <td>'. $data['Category_idCategory'].'</td>
                    <td>
                        <a href="admin.php?&table=category&edit='.$data['idCategory'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=category&remove='.$data['idCategory'].'" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            //print_r($data);
            //echo '<br>';
        }
        echo '</table>';
    }
}

echo '</div>';
?>


