<?php
$db = new Database();

if (isset($_POST['submitUser'])) {
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : null);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : null);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $privileges = htmlspecialchars(!empty($_POST['privileges']) ? trim($_POST['privileges']) : null);

    if ($db->userExist($email)) {
        echo("<p class='alert'>Uzivatel jiz existuje</p>");
    } else {
        $hashpsw = password_hash($pass, PASSWORD_BCRYPT);
        $db->insertUser($fname, $lname, $email, $hashpsw, $privileges);
        header('Location:/admin.php?table=user');
    }
}

if (isset($_POST['submitCategory'])) {
    $name = htmlspecialchars(!empty($_POST['categoryName']) ? trim($_POST['categoryName']) : null);
    $desc = htmlspecialchars(!empty($_POST['categoryDescription']) ? trim($_POST['categoryDescription']) : null);
    $idParent = htmlspecialchars(!empty($_POST['idParent']) ? trim($_POST['idParent']) : null);

    if (empty($name)) {
        $name = 'None';
    }
    if (empty($idParent)) {
        $idParent = NULL;
    }
    if ($db->categoryExist($name)) {
        echo("<p class='alert'>Kategorie jiz existuje</p>");
    } else {
        $db->insertCategory($name, $desc, $idParent);
        header('Location:/admin.php?table=category');
    }
}

if (isset($_POST['submitProduct'])) {
    $name = htmlspecialchars(!empty($_POST['productName']) ? trim($_POST['productName']) : null);
    $price = htmlspecialchars(!empty($_POST['productPrice']) ? trim($_POST['productPrice']) : null);
    $desc = htmlspecialchars(!empty($_POST['productDescription']) ? trim($_POST['productDescription']) : null);
    $vat = htmlspecialchars(!empty($_POST['productVat']) ? trim($_POST['productVat']) : null);
    $file = $_FILES['file'];

    $filename = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.', $filename);
    $fileActualExt = strtolower(end($fileExt));
    $allowed = array('jpg', 'jpeg', 'png', 'bmp');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileDestination = 'img/' . $filename;
                move_uploaded_file($fileTmpName, $fileDestination);
                $db->insertProduct($name, $price, $desc, $vat, $filename);
                header('Location:/admin.php?table=product');
            } else {
                echo("<p class='alert'>Soubor je příliš velký</p>");
            }
        } else {
            echo("<p class='alert'>Chyba při nahravaní souboru</p>");
        }
    } else {
        echo("<p class='alert'>Nepodporovany typ souboru</p>");
    }
}

if (isset($_POST['userEditSubmit'])) {
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : null);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : null);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $privileges = htmlspecialchars(!empty($_POST['privileges']) ? trim($_POST['privileges']) : null);

    $user = $db->getUserById($_GET['edit']);
    if (strcmp($user['password'], $pass) == 0) {
        $db->updateUser($_GET['edit'], $fname, $lname, $email, $user['password'], $privileges);
    } else {
        $hashpsw = password_hash($pass, PASSWORD_BCRYPT);
        $db->updateUser($_GET['edit'], $fname, $lname, $email, $hashpsw, $privileges);
    }

    header('Location:/admin.php?table=user');
}

if (isset($_POST['productEditSubmit'])) {
    $name = htmlspecialchars(!empty($_POST['productName']) ? trim($_POST['productName']) : null);
    $price = htmlspecialchars(!empty($_POST['productPrice']) ? trim($_POST['productPrice']) : null);
    $desc = htmlspecialchars(!empty($_POST['productDescription']) ? trim($_POST['productDescription']) : null);
    $vat = htmlspecialchars(!empty($_POST['productVat']) ? trim($_POST['productVat']) : null);

    $product = $db->getProductById($_GET['edit']);
    $file = $_FILES['file'];
    if (!empty($_FILES['file']['name'])) {
        $filename = $_FILES['file']['name'];
        $fileTmpName = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];

        $fileExt = explode('.', $filename);
        $fileActualExt = strtolower(end($fileExt));
        $allowed = array('jpg', 'jpeg', 'png', 'bmp');

        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 1000000) {
                    $fileDestination = 'img/' . $filename;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    //UPDATE A DELETE OLD OBRAZEK z IMG
                    unlink('img/' . $product['imgLink']);
                    $db->updateProduct($_GET['edit'], $name, $price, $desc, $vat, $filename);
                    header('Location:/admin.php?table=product');
                } else {
                    echo("<p class='alert'>Soubor je příliš velký</p>");
                }
            } else {
                echo("<p class='alert'>Chyba při nahravaní souboru</p>");
            }
        } else {
            echo("<p class='alert'>Nepodporovany typ souboru</p>");
        }
    } else {
        $db->updateProduct($_GET['edit'], $name, $price, $desc, $vat, $product['imgLink']);
    }

    header('Location:/admin.php?table=product');
}

if (isset($_POST['categoryEditSubmit'])) {
    $name = htmlspecialchars(!empty($_POST['categoryName']) ? trim($_POST['categoryName']) : null);
    $desc = htmlspecialchars(!empty($_POST['categoryDescription']) ? trim($_POST['categoryDescription']) : null);
    $idParent = htmlspecialchars(!empty($_POST['categoryIdCategory']) ? trim($_POST['categoryIdCategory']) : null);

    $db->updateCategory($_GET['edit'], $name, $desc, $idParent);
    header('Location:/admin.php?table=category');
}
?>


<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>


<form action="">
    <input type="submit" name="table" value="user">
    <!--<input type="submit" name="table" value="order">
    <input type="submit" name="table" value="invoice">-->
    <input type="submit" name="table" value="category">
    <input type="submit" name="table" value="product">
</form>


<?php
//ADD
if (isset($_GET['table'])) {
    if ($_GET['table'] == 'user') {
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
                <input type="submit"Přidej name="submitUser" value="Add">
           </form><br>';
    }

    if ($_GET['table'] == 'category') {
        echo '<form method="post" action="">
                <label>Category name</label>
                <input type="text" name="categoryName" required><br>
                <label>Description</label>
                <input type="text" name="categoryDescription" required><br>
                <label>ID Parent</label>
                <input type="text" name="idParent"><br>
                <input type="submit"Přidej name="submitCategory" value="Add">
           </form><br>';
    }

    if ($_GET['table'] == 'product') {
        echo '<form method="post" action="" enctype="multipart/form-data">
                <label>Product name</label>
                <input type="text" name="productName" required><br>
                <label>Price</label>
                <input type="number" name="productPrice" required><br>
                <label>Description</label>
                <textarea rows=10 cols="100" type="text" name="productDescription" required></textarea><br>
                <label>VAT</label>
                <input type="number" name="productVat" required><br>
                <input type="file" name="file"><br>
                <input type="submit"Přidej name="submitProduct" value="Add">
           </form><br>';
    }
}

//EDIT+REMOVE
if (isset($_GET['table'])) {
    if ($_GET['table'] == 'user') {
        if (isset($_GET['edit'])) {
            $user = $db->getUserById($_GET['edit']);

            echo '<div class="addForm"><form method="post" action="">
                <label>Jmeno</label>
                <input type="text" name="fname" value="' . $user['firstName'] . '" required><br>
                <label>Prijmeni</label>
                <input type="text" name="lname" value="' . $user['lastName'] . '"required><br>
                <label>Email</label>
                <input type="email" name="email" value="' . $user['email'] . '"required><br>
                <label>Heslo</label>
                <input type="text" name="psw" value="' . $user['password'] . '"required><br>
                <label>Prava</label>
                <select name="privileges">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select><br>
                <input type="submit"Přidej name="userEditSubmit" value="Edit">
           </form><br></div>';

        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $db->deleteUser($id);
        }

    }

    if ($_GET['table'] == 'category') {
        if (isset($_GET['edit'])) {

            $category = $db->getCategoryById($_GET['edit']);

            echo '<div class="addForm"><form method="post" action="">
                <label>ID category</label>
                <input type="number" name="idCategory" value="' . $category['idCategory'] . '" readonly><br>
                <label>Category name</label>
                <input type="text" name="categoryName" value="' . $category['categoryName'] . '" required><br>
                <label>Description</label>
                <input type="text" name="categoryDescription" value="' . $category['description'] . '"required><br>
                <label>ID parent</label>
                <input type="number" name="categoryIdCategory" value="' . $category['Category_idCategory'] . '"><br>
                <input type="submit"Přidej name="categoryEditSubmit" value="Edit">
           </form><br></div>';

        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $db->deleteCategory($id);
        }
    }

    if ($_GET['table'] == 'product') {
        if (isset($_GET['edit'])) {

            $product = $db->getProductById($_GET['edit']);

            echo '<div class="addForm"><form method="post" action="" enctype="multipart/form-data">
                <label>Product name</label>
                <input type="text" name="productName" value="' . $product['productName'] . '" required><br>
                <label>Price</label>
                <input type="number" name="productPrice" value="' . $product['price'] . '"required><br>
                <label>Description</label>
                <textarea rows=10 cols=100 type="text" name="productDescription" required>' . $product['productDescription'] . '</textarea><br>
                <label>VAT</label>
                <input type="number" name="productVat" value="' . $product['vat'] . '"required><br>
                <label>Load new image</label>
                <input type="file" name="file"><br>
                <input type="submit" name="productEditSubmit" value="Edit">
           </form><br></div>';

        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $product = $db->getProductById($id);
            unlink('img/' . $product['imgLink']);
            $db->deleteProduct($id);
        }
    }
}

echo '<div class="adminTables">';

if (isset($_GET['table'])) {
    $tab = $_GET['table'];
    $tableData = $db->getAllFromTable($tab);
    if ($tab == 'user') {
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
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $data['idUser'] . '</td>
                    <td>' . $data['firstName'] . '</td>
                    <td>' . $data['lastName'] . '</td>
                    <td>' . $data['email'] . '</td>
                    <td>' . $data['password'] . '</td>
                    <td>' . $data['privileges'] . '</td>
                    <td>
                        <a href="admin.php?&table=user&edit=' . $data['idUser'] . '" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=user&remove=' . $data['idUser'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
    if ($tab == 'product') {
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
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $data['idProduct'] . '</td>
                    <td>' . $data['productName'] . '</td>
                    <td>' . $data['price'] . '</td>
                    <td>' . $data['productDescription'] . '</td> <!-- .substr($data[\'productDescription\'], 0,100).... -->
                    <td>' . $data['vat'] . '</td>
                    <td>' . $data['imgLink'] . '</td>
                    <td>
                        <a href="admin.php?&table=product&edit=' . $data['idProduct'] . '" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=product&remove=' . $data['idProduct'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
    if ($tab == 'category') {
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
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $data['idCategory'] . '</td>
                    <td>' . $data['categoryName'] . '</td>
                    <td>' . $data['description'] . '</td>
                    <td>' . $data['Category_idCategory'] . '</td>
                    <td>
                        <a href="admin.php?&table=category&edit=' . $data['idCategory'] . '" class="btn btn-info">Edit</a>
                        <a href="admin.php?&table=category&remove=' . $data['idCategory'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
}

echo '</div>';
?>


