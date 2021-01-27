<head>
    <link rel="stylesheet" href="../css/cart.css">
</head>

<div class="cart">
    <?php
    $sumPrice = 0;
    if (isset($_GET['smazat'])) {
        $id = $_GET['smazat'];
        //$sumPrice = $sumPrice - $_SESSION['kosik'][$id]['price'];
        $_SESSION['kosik'][$id]->decrementCount();
        //unset($_SESSION['kosik'][$id]);
        header('Location:/cart.php');
    }
    if (isset($_GET['add'])) {
        $id = $_GET['add'];
        //$sumPrice = $sumPrice - $_SESSION['kosik'][$id]['price'];
        $_SESSION['kosik'][$id]->incrementCount();
        //unset($_SESSION['kosik'][$id]);
        header('Location:/cart.php');
    }
    for ($i = 0; $i < $_SESSION['kosikPocet']; $i++) {
        //foreach($_SESSION['kosik'] as $item){
        if ($_SESSION['kosik'][$i]->getCount() > 0) {
            //$tmp = $_SESSION['kosik'][0];
            //print_r($tmp);
            //echo $count[0];
            //print_r($count);
            $item = $_SESSION['kosik'][$i]->getItem();
            $count = $_SESSION['kosik'][$i]->getCount();
            //echo $item;
            //$product = $item->getItem();
            echo '<div class="itemInCart">';
            echo '<img class="productImg" src="../img/' . $item['imgLink'] . '">';
            echo '<div class="productName">' . $item['productName'] . '</div>';
            echo '<div class="productPrice">' . $item['price'] . '</div>';
            echo '<div class="productCount">' . $count . '</div>';
            echo '<a href="cart.php?&add=' . $i . '">&#10133</a>';
            echo '<a href="cart.php?&smazat=' . $i . '">&#10134</a>';
            echo '</div>';
            $sumPrice += $item['price']*$count;
            //echo $sumPrice;

        }
    }


    ?>
</div>

<div class="summary">
    <div>
        <?php
        echo '<div class="summaryElement">Celková cena: ' . $sumPrice . ',-Kč</div>';
        echo '<div class="summaryElement"><a href="../">Pokračovat v nákupu</a></div>';
        if ($sumPrice != 0) {
            echo '<div class="summaryElement"><a href="payment.php">Zaplatit</a></div>';
        } else {
            echo '<div class="summaryElement"><a href="#">Zaplatit</a> - váš košík je zatím prázdný</div>';
        }
        ?>
    </div>
</div>
