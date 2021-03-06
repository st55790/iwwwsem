<?php
if (!isset($_SESSION['prava']) or $_SESSION['prava'] != 'admin') {
    header('Location:/');
}

if (isset($_POST['submitUser'])) {
    $dbUser = new UserDB();
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : null);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : null);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $privileges = htmlspecialchars(!empty($_POST['privileges']) ? trim($_POST['privileges']) : null);

    if ($dbUser->userExist($email)) {
        echo("<p class='alert'>Uzivatel jiz existuje</p>");
    } else {
        $hashpsw = password_hash($pass, PASSWORD_BCRYPT);
        $dbUser->insertUser($fname, $lname, $email, $hashpsw, $privileges);
        header('Location:/admin.php?table=user');
    }
}

if (isset($_POST['submitCategory'])) {
    $dbCategory = new CategoryDB();
    $name = htmlspecialchars(!empty($_POST['categoryName']) ? trim($_POST['categoryName']) : null);
    $desc = htmlspecialchars(!empty($_POST['categoryDescription']) ? trim($_POST['categoryDescription']) : null);
    $idParent = htmlspecialchars(!empty($_POST['idParent']) ? trim($_POST['idParent']) : null);

    if (empty($name)) {
        $name = 'None';
    }
    if (empty($idParent)) {
        $idParent = NULL;
    }
    if ($dbCategory->categoryExist($name)) {
        echo("<p class='alert'>Kategorie jiz existuje</p>");
    } else {
        if ($idParent == 0) {
            $idParent = NULL;
        } else if (!$dbCategory->categoryExistById($idParent)) {
            $idParent = '61';
        }
        $dbCategory->insertCategory($name, $desc, $idParent);
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
                $dbProduct = new ProductDB();
                $fileDestination = 'img/' . $filename;
                move_uploaded_file($fileTmpName, $fileDestination);
                $dbProduct->insertProduct($name, $price, $desc, $vat, $filename);

                $newProduct = $dbProduct->getProductByName($name);
                if (!empty($_POST['checkCats'])) {
                    $db = new Database();
                    foreach ($_POST['checkCats'] as $value) {
                        $db->insertProductHasCategory($newProduct['idProduct'], $value);
                    }
                }

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
    $dbUser = new UserDB();
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : null);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : null);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $privileges = htmlspecialchars(!empty($_POST['privileges']) ? trim($_POST['privileges']) : null);

    $user = $dbUser->getUserById($_GET['edit']);
    if (strcmp($user['password'], $pass) == 0) {
        $dbUser->updateUser($_GET['edit'], $fname, $lname, $email, $user['password'], $privileges);
    } else {
        $hashpsw = password_hash($pass, PASSWORD_BCRYPT);
        $dbUser->updateUser($_GET['edit'], $fname, $lname, $email, $hashpsw, $privileges);
    }

    header('Location:/admin.php?table=user');
}

if (isset($_POST['productEditSubmit'])) {
    $dbProduct = new ProductDB();
    $name = htmlspecialchars(!empty($_POST['productName']) ? trim($_POST['productName']) : null);
    $price = htmlspecialchars(!empty($_POST['productPrice']) ? trim($_POST['productPrice']) : null);
    $desc = htmlspecialchars(!empty($_POST['productDescription']) ? trim($_POST['productDescription']) : null);
    $vat = htmlspecialchars(!empty($_POST['productVat']) ? trim($_POST['productVat']) : null);

    $product = $dbProduct->getProductById($_GET['edit']);
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
                    $dbProduct->updateProduct($_GET['edit'], $name, $price, $desc, $vat, $filename);

                    if (!empty($_POST['checkCats'])) {
                        $db = new Database();
                        $db->deleteProductHasCategory($_GET['edit']);
                        foreach ($_POST['checkCats'] as $value) {
                            $db->insertProductHasCategory($_GET['edit'], $value);
                        }
                    }
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
        $dbProduct->updateProduct($_GET['edit'], $name, $price, $desc, $vat, $product['imgLink']);
    }

    if (!empty($_POST['checkCats'])) {
        $db = new Database();
        $db->deleteProductHasCategory($_GET['edit']);
        foreach ($_POST['checkCats'] as $value) {
            $db->insertProductHasCategory($_GET['edit'], $value);
        }
    }
    header('Location:/admin.php?table=product');
}

if (isset($_POST['categoryEditSubmit'])) {
    $dbCategory = new CategoryDB();
    $name = htmlspecialchars(!empty($_POST['categoryName']) ? trim($_POST['categoryName']) : null);
    $desc = htmlspecialchars(!empty($_POST['categoryDescription']) ? trim($_POST['categoryDescription']) : null);
    $idParent = htmlspecialchars(!empty($_POST['categoryIdCategory']) ? trim($_POST['categoryIdCategory']) : null);
    if (!$dbCategory->categoryExistById($idParent)) {
        $idParent = '39 ';
    }
    $dbCategory->updateCategory($_GET['edit'], $name, $desc, $idParent);
    header('Location:/admin.php?table=category');
}
?>


<head>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<!--https://stackoverflow.com/questions/590018/getting-all-selected-checkboxes-in-an-array-->
<script>
    var array = []
    var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

    for (var i = 0; i < checkboxes.length; i++) {
        array.push(checkboxes[i].value)
    }
</script>

<form action="">
    <input type="submit" name="table" value="user">
    <input type="submit" name="table" value="category">
    <input type="submit" name="table" value="product">
    <input type="submit" name="table" value="orders">
    <input type="submit" name="json" value="loadCategoryFromJson">
    <input type="submit" name="json" value="saveCategoryFromJson">
</form>

<?php
if (isset($_GET['json'])) {
    $dbCategory = new CategoryDB();

    if ($_GET['json'] == 'loadCategoryFromJson') {
        if (file_exists('category.json')) {
            $json = file_get_contents('category.json');
            $arrayCat = json_decode($json, true);
            foreach ($arrayCat as $cat) {
                if (!$dbCategory->categoryExist($cat['categoryName'])) {
                    $idParent = $cat['Category_idCategory'];
                    if ($cat['Category_idCategory'] == '') {
                        $idParent = NULL;
                    }
                    $dbCategory->insertCategory($cat['categoryName'], $cat['description'], $idParent);
                }
            }
            echo 'Json was loaded';
        } else {
            echo 'Je potřeba dát do složky soubor: "category.json", který obsahuje json data pro přidání do tabulky category';
        }
    }

    if ($_GET['json'] == 'saveCategoryFromJson') {
        $allCat = $dbCategory->getAllCategory();
        $json = json_encode($allCat);
        //$bytes = file_put_contents('category.json', $json);

        //$detail1 = json_decode(file_get_contents("http://localhost/category.json"), true);
        //$detail2 = json_decode(file_get_contents($detail1['data']),true);

        //$file_url = $detail2['data'];
        ob_start();
        //echo $json;
        $htmlStr = ob_get_contents();
        ob_end_clean();
        $file_url = 'category.json';
        //file_put_contents($file_url, $htmlStr);

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");

        ob_clean();
        flush();
        //readfile($file_url);
        echo $json;
        exit();

        echo 'Json was created!';
    }

    //header('Location:/admin.php');
}
?>

<?php
if (isset($_GET['showOrder'])) {
    echo '<div class="order">';
    $db = new Database();
    $orderDetail = $db->getCompleteOrder($_GET['showOrder']);

    echo 'Jméno: ' . $orderDetail['firstName'] . ' ' . $orderDetail['lastName'] . '<br>';
    echo 'Telefon: ' . $orderDetail['phone'] . '<br>';
    echo 'Adresa: ' . $orderDetail['city'] . ' ' . $orderDetail['postCode'] . '<br>';

    $products = $db->getAllProductFromOrderByIdOrder($_GET['showOrder']);
    $sum = $db->getSumOrder($_GET['showOrder']);

    //echo 'Celková cena objednávky: '. $sum .'<br>';
    //echo 'tabulka produktu<br>';

    $sum = 0;
    $dbProduct = new ProductDB();

    echo '<table>
            <thead>
                <th>Jméno produktu</th>
                <th>Počet</th>
                <th>Cena za kus v kč</th>
                <th>DPH</th>
            </thead>';

    foreach ($products as $item) {
        $product = $dbProduct->getProductById($item['Product_idProduct']);
        echo '<tr><td>' . $product['productName']. '</td>';
        echo '<td>' . $item['quantity']. '</td>';
        echo '<td>' . $product['price']. '</td>';
        echo '<td>' . $product['vat']. '</td>';

        echo '</tr>';
        $sum += $product['price'] * $item['quantity'];
    }

    echo 'Celkova cena objednávky: ' . $sum . ',-Kč';
    echo '</table></div>';


    echo '</div>';
}
?>


<?php
//ADD
if (isset($_GET['table']) and !isset($_GET['edit'])) {
    echo '<div class=addForm>';
    if ($_GET['table'] == 'user') {
        echo '<form method="post" action="">
                <label>Firstname</label>
                <input type="text" name="fname" required><br>
                <label>Lastname</label>
                <input type="text" name="lname" required><br>
                <label>Email</label>
                <input type="email" name="email" required><br>
                <label>Password</label>
                <input type="password" name="psw" required><br>
                <label>Privileges</label><br>
                <select name="privileges">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select><br><br>
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
                <input type="text" name="idParent"><br><br>
                <input type="submit"Přidej name="submitCategory" value="Add">
           </form><br>';
    }

    if ($_GET['table'] == 'product') {
        $dbCategory = new CategoryDB();
        $allCat = $dbCategory->getAllCategory();
        echo '<form method="post" action="" enctype="multipart/form-data">
                <label>Product name</label>
                <input type="text" name="productName" required><br>
                <label>Price</label>
                <input type="number" step="0.01" min="0" name="productPrice" required><br>
                <label>Description</label><br>
                <!--<p><span id="productDescription" class="textarea" role="textbox" contenteditable ></span></p>
                <textarea rows=10 cols="110 type="text" name="productDescription" required></textarea><br>
                -->
                    <div class="comment">
                        <textarea class="textinput"  name="productDescription" required></textarea>
                    </div>
                <label>VAT</label><br>
                <select type="number" name="productVat">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="21">21</option>
                </select>
                <!--<input type="number" step="0.01" min="0" name="productVat" required><br><br>-->
                <div class="checkboxes">
                ';
        foreach ($allCat as $cat) {
            echo '<div class="checkValue"><input type="checkbox" name="checkCats[]" value="' . $cat['idCategory'] . '">';
            echo '<label>' . $cat['categoryName'] . '</label></div>';
        }
        echo '</div><br>
                <input type="file" name="file"><br><br>
                <input onclick="getDes()" type="submit"Přidej name="submitProduct" value="Add">
           </form><br>';
    }
    echo '</div>';
}

//EDIT+REMOVE
if (isset($_GET['table'])) {
    if ($_GET['table'] == 'user') {
        $dbUser = new UserDB();
        if (isset($_GET['edit'])) {

            $user = $dbUser->getUserById($_GET['edit']);

            echo '<div class="addForm"><form method="post" action="">
                <label>Firstname</label>
                <input type="text" name="fname" value="' . $user['firstName'] . '" required><br>
                <label>Lastname</label>
                <input type="text" name="lname" value="' . $user['lastName'] . '"required><br>
                <label>Email</label>
                <input type="email" name="email" value="' . $user['email'] . '"required><br>
                <label>Password</label>
                <input type="text" name="psw" value="' . $user['password'] . '"required><br>
                <label>Privileges</label><br>
                <select name="privileges">
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                </select><br><br>
                <input type="submit"Přidej name="userEditSubmit" value="Edit">
           </form><br></div>';
        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $dbUser->deleteUser($id);
            header('Location:/admin.php?table=user');
        }

    }

    if ($_GET['table'] == 'category') {
        $dbCategory = new CategoryDB();
        if (isset($_GET['edit'])) {

            $category = $dbCategory->getCategoryById($_GET['edit']);

            echo '<div class="addForm"><form method="post" action="">
                <label>ID category</label>
                <input type="number" name="idCategory" value="' . $category['idCategory'] . '" readonly><br>
                <label>Category name</label>
                <input type="text" name="categoryName" value="' . $category['categoryName'] . '" required><br>
                <label>Description</label>
                <input type="text" name="categoryDescription" value="' . $category['description'] . '"required><br>
                <label>ID parent</label>
                <input type="number" name="categoryIdCategory" value="' . $category['Category_idCategory'] . '"><br><br>
                <input type="submit"Přidej name="categoryEditSubmit" value="Edit">
           </form><br></div>';
        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $dbCategory->deleteCategory($id);
            header('Location:/admin.php?table=category');
        }
    }

    if ($_GET['table'] == 'product') {
        $dbProduct = new ProductDB();
        if (isset($_GET['edit'])) {
            $product = $dbProduct->getProductById($_GET['edit']);
            $dbCategory = new CategoryDB();
            $db = new Database();
            $productCat = $db->getAllProductCategory($_GET['edit']);
            $allCat = $dbCategory->getAllCategory();

            echo '<div class="addForm"><form method="post" action="" enctype="multipart/form-data">
                <label>Product name</label>
                <input type="text" name="productName" value="' . $product['productName'] . '" required><br>
                <label>Price</label>
                <input type="number" name="productPrice" value="' . $product['price'] . '"required><br>
                <label>Description</label><br>
                <!--<textarea rows=10 cols=100 type="text" name="productDescription" required>' . $product['productDescription'] . '</textarea><br>-->
                <div class="comment">
                    <textarea class="textinput"  name="productDescription" required>' . $product['productDescription'] . '</textarea>
                </div>
                <label>VAT</label><br>
                <select type="number" name="productVat">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="21">21</option>
                </select>
                <input type="number" name="productVat" value="' . $product['vat'] . '"required><br><br>
                <div class="checkboxes">
                ';
            foreach ($allCat as $cat) {
                $exist = false;
                echo '<div class="checkValue">';
                foreach ($productCat as $pc) {
                    if ($pc['idCategory'] === $cat['idCategory']) {
                        echo '<input type="checkbox" name="checkCats[]" value="' . $cat['idCategory'] . '" checked>';
                        $exist = true;
                        break;
                    }
                }
                if (!$exist) {
                    echo '<input type="checkbox" name="checkCats[]" value="' . $cat['idCategory'] . '">';
                }
                echo '<label>' . $cat['categoryName'] . '</label></div>';
            }
            echo '</div><br>
                <label>Load new image</label>
                <input type="file" name="file"><br><br>
                <input type="submit" name="productEditSubmit" value="Edit">
           </form><br></div>';
        }

        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $product = $dbProduct->getProductById($id);
            unlink('img/' . $product['imgLink']);
            $dbProduct->deleteProduct($id);
            header('Location:/admin.php?table=product');
        }
    }

    if($_GET['table'] == 'orders'){
        if (isset($_GET['remove'])) {
            $id = $_GET['remove'];
            $dbOrder = new OrderDB();
            $dbOrder->deleteFullOrder($id);
            header('Location:/admin.php?table=orders');
        }
    }
}

//TABLES
if (isset($_GET['table'])) {
    $db = new Database();
    $tab = $_GET['table'];
    $tableData = $db->getAllFromTable($tab);
    echo '<div class="adminTables">';
    echo '<div>';
    if ($tab == 'user') {
        echo '<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Privileges</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>';
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
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>VAT</th>
                            <th>Img link</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>';
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $data['idProduct'] . '</td>
                    <td>' . $data['productName'] . '</td>
                    <td>' . $data['price'] . '</td>
                    <td id="tdDescription">' . $data['productDescription'] . '</td>
                    <td>' . $data['vat'] . '</td>
                    <td><img width="100px" src="../img/' . $data['imgLink'] . '"></td>
                    <td>
                        <a href="admin.php?table=product&edit=' . $data['idProduct'] . '" class="btn btn-info">Edit</a>
                        <a href="admin.php?table=product&remove=' . $data['idProduct'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
    if ($tab == 'category') {
        echo '<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category name</th>
                            <th>Description</th>
                            <th>ID parent</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>';
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $data['idCategory'] . '</td>
                    <td>' . $data['categoryName'] . '</td>
                    <td>' . $data['description'] . '</td>
                    <td>' . $data['Category_idCategory'] . '</td>
                    <td>
                        <a href="admin.php?table=category&edit=' . $data['idCategory'] . '" class="btn btn-info">Edit</a>
                        <a href="admin.php?table=category&remove=' . $data['idCategory'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
        }
        echo '</table>';
    }
    if ($tab == 'orders') {
        echo '<table>
                    <thead>
                        <tr>
                            <th>Objednávka</th>
                            <th>User_idUser</th>
                            <th>Time</th>
                            <th colspan="2">Action</th>
                        </tr>
                    </thead>';
        $count = 1;
        foreach ($tableData as $data) {
            echo '<tr>
                    <td>' . $count . '</td>
                    <td>' . $data['firstName'] . ' ' . $data['lastName'] . '</td>
                    <td>' . $data['timeOrder'] . '</td>
                    <td>
                        <a href="admin.php?showOrder=' . $data['idOrder'] . '" class="btn btn-info">Show</a>
                        <a href="admin.php?table=orders&remove=' . $data['idOrder'] . '" class="btn btn-info">Delete</a>
                    </td>
                  </tr>';
            $count++;
        }
        echo '</table>';
    }
    echo '</div>';
    echo '</div>';
}
?>


