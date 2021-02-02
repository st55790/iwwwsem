<?php
/*
function show_menu(){
    $conn = Connection::getPdoInstance();

    $menus = '';

    $menus .= generate_multilevel_menus();

    return $menus;
}

function generate_multilevel_menus($parent_id = NULL){
    $menu = '';
    $conn = Connection::getPdoInstance();
    $sql = '';
    if(is_null($parent_id)){
        $sql = $conn->prepare("SELECT * FROM category WHERE Category_idCategory IS NULL");
    }else{
        $sql = $conn->prepare("SELECT * FROM category WHERE Category_idCategory = $parent_id");
    }
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    $row = $sql->fetch();

 //

    foreach($result as $row){
        if($row['categoryName']){
            $menu .= '<li><a href="#">'.$row['categoryName'].'</a></li>';
            echo $row['categoryName'];

        }else{
            $menu .= '<li><a href="#">'.$row['categoryName'].'</a></li>';
        }
        $menu .= '<ul>'.generate_multilevel_menus($row['idCategory']).'</ul>';
        $menu .= '</li>';
    }
    return $menu;
}*/
?>

<head>
    <link rel="stylesheet" href="../css/header.css">
</head>

<?php

function show_menu()
{
    $menus = '';
    $menus .= generateChild(NULL);
    return $menus;
}

function generateChild($parrent_id)
{
    $dbCategory = new CategoryDB();
    $menu = '';
    $result = $dbCategory->getCategories($parrent_id);

    foreach ($result as $row) {
        if ($row['idCategory']) {
            $menu .= '<li><a href="products.php?p=' . $row['description'] . '">' . $row['categoryName'] . '</a>';
        }
        $menu .= '<ul class="dropdown">' . generateChild($row['idCategory']) . '</ul>';
        $menu .= '</li>';

    }
    return $menu;
}
?>


<div class="menu">
    <div><a class="logoNav" href="../"><img src="../img/logo.png"></a></div>

    <label for="hamburger">&#9776</label>
    <input type="checkbox" id="hamburger"/>

    <nav class="nav-categories">
        <ul class="ul-category">
            <?=
            show_menu()
            ?>
        </ul>
    </nav>
    <div class="nav-account-cart">
        <?php

        if (isset($_SESSION['prava']) && ($_SESSION['prava'] == 'admin')) {
            echo '<a href="admin.php">&#128272</a>';
        }
        if (!isset($_SESSION['prava'])) {

        } else {
            echo '<a href="cart.php">&#128722;</a>';
            echo '<a href="logout.php">&#9940;</a>';
        }

        if (isset($_SESSION['prava'])) {
            echo '<a href="account.php">&#129489</a>';
        } else {
            echo '<a href="login.php">&#129489</a>';
        }
        ?>

    </div>
</div>

