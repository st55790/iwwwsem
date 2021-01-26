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

function show_menu(){
    $menus = '';
    $menus .= gen2(NULL);
    return $menus;
}

function gen2($parrent_id){
    $menu = '';

    $db = new Database();

    $result = $db->getCategories($parrent_id);

    //echo $row['idCategory'];

    //$rows = $result->fetchAll(PDO::FETCH_CLASS, 'ArrayObject');
    //print_r($result);
    //print_r($row);
    foreach($result as $row){
        if($row['idCategory']){
            $menu .= '<li><a href="products.php?&p='.$row['description'].'">'.$row['categoryName'].'</a>';
            //echo $row['categoryName'];
        }else{

        }
        //Category_idCategory
        $menu .= '<ul class="dropdown">'.gen2($row['idCategory']).'</ul>';
        $menu .= '</li>'; //tady něco chybí <li zacatek

    }
    //echo $menu;
    return $menu;
}


?>



<div class="menu">
    <div><a class="logoNav" href="../"><img src="../img/logo.png"></a></div>

    <nav class="nav-categories">
        <ul class="ul-category">
            <?=
            show_menu()
            ?>
            <!--<li>
                <a href="#">Nike</a>
                <ul class="dropdown">
                    <li><a href="#">Merucirial</a></li>
                    <li><a href="#">Tiempo</a></li>
                    <li><a href="#">Hypervenom</a></li>
                    <li><a href="#">Superfly</a>
                        <ul class="dropdown">
                            <li><a href="#">Merucirial</a></li>
                            <li><a href="#">Tiempo</a></li>
                            <li><a href="#">Hypervenom</a></li>
                            <li><a href="#">Superfly</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#">Adidas</a>
                <ul class="dropdown">
                    <li><a href="#">F50</a></li>
                    <li><a href="#">F10</a></li>
                    <li><a href="#">Kaiser</a></li>
                    <li><a href="#">Origin</a></li>
                </ul>
            </li>
            <li><a href="#">Puma</a>
                <ul class="dropdown">
                    <li><a href="#">Pro</a></li>
                    <li><a href="#">Speed</a></li>
                </ul>
            </li>-->
        </ul>
    </nav>
    <div class="nav-account-cart">
        <?php

        if(isset($_SESSION['prava']) && ($_SESSION['prava'] == 'admin')){
            echo '<a href="admin.php">&#128272</a>';
        }
        if(!isset($_SESSION['prava'])){

        }else{
            echo '<a href="cart.php">&#128722;</a>';
            echo '<a href="logout.php">&#9940;</a>';
        }

        if(isset($_SESSION['prava'])){
            echo '<a href="account.php">&#129489</a>';
        }else{
            echo '<a href="login.php">&#129489</a>';
        }
        ?>

    </div>
</div>

