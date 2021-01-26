<?php
//echo 'ADMIN PANEL';
$db = new Database();
?>
<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<script>

</script>

<form action="">
    <label for="cars">Vyberte tabulku, se kterou chcete pracovat:</label>
    <select name="tables" id="tables">
        <option value="user">Uživatelé</option>
        <option value="product">Produkty</option>
        <option value="category">Kategorie</option>
    </select>
    <br><br>
    <input class="submitInput" type="submit" value="Submit">
</form>

<?php

    if(isset($_GET['tables'])){
        //echo '<button type="button">Přidej</button>';
    }

if(isset($_GET['tables'])){
    $tab = $_GET['tables'];
    //echo $tab;
    $tableData = $db->getAllFromTable($tab);
    /*if(!is_null($tableData)){
        //print_r($tableData);
        foreach ($tableData as $data){
            //print_r($data);
            //echo '<br>';

        }
    }*/
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
                        <a href="admin.php?tables=user#?edit='.$data['idUser'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?tables=user#?remove='.$data['idUser'].'" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            //print_r($data);
            //echo '<br>';
            }
        echo '</table>';
    }
    if($tab == 'product'){
        //echo 'PRODUCT';
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
                        <a href="admin.php?tables=product#?edit='.$data['idProduct'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?tables=product#?remove='.$data['idProduct'].'" class="btn btn-info">Delete</a>
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
                        <a href="admin.php?tables=category#?edit='.$data['idCategory'].'" class="btn btn-info">Edit</a>
                        <a href="admin.php?tables=category#?remove='.$data['idCategory'].'" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            //print_r($data);
            //echo '<br>';
        }
        echo '</table>';
    }
}
?>

<?php

if(isset($_GET['edit'])){
    echo 'test';
}
if(isset($_GET['remove'])){
    echo 'test';
}
?>

