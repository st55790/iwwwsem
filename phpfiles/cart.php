<head>
    <link rel="stylesheet" href="../css/cart.css">
</head>

<div class="cart">
<?php
$sumPrice = 0;
if(isset($_GET['smazat'])){
    $id = $_GET['smazat'];
    $sumPrice = $sumPrice - $_SESSION['kosik'][$id]['price'];
    unset($_SESSION['kosik'][$id]);
    header('Location:/cart.php');
}
for ($i = 0; $i < $_SESSION['kosikPocet']; $i++){
    if(isset($_SESSION['kosik'][$i])){
        echo '<div class="itemInCart">';
        echo '<img class="productImg" src="../img/' . $_SESSION['kosik'][$i]['imgLink'] . '">';
        echo '<div class="productName">'.$_SESSION['kosik'][$i]['productName'].'</div>';
        echo '<div class="productPrice">'.$_SESSION['kosik'][$i]['price'].'</div>';
        //echo $_SESSION['kosik'][$i]['idProduct'];
        echo  '<a href="cart.php?&smazat='.$i.'">&#10060</a><br>';
        echo '</div>';
        $sumPrice += $_SESSION['kosik'][$i]['price'];
        //echo $sumPrice;
    }
}


?>
</div>

<div class="summary">
    <div>
    <?php
        echo 'Celková cena: '.$sumPrice.',-Kč<br>';
        echo '<a href="../">Pokračovat v nákupu</a><br>';
        if($sumPrice != 0){
            echo '<a href="payment.php">Zaplatit</a><br>';
        }else{
            echo '<a href="#">Zaplatit</a> - váš košík je zatím prázdný<br>';
        }
    ?>
    </div>
</div>
