<?php

echo 'Cele jmeno: '. $_GET['firstname'];
echo 'Email: '. $_GET['email'];
echo 'Adresa: '. $_GET['address'];
echo 'Město: '. $_GET['city'];
echo 'Stát: '. $_GET['state'];
echo 'PSČ: '. $_GET['zip'];

$sumPrice = 0;
for ($i = 0; $i < $_SESSION['kosikPocet']; $i++){
    if(isset($_SESSION['kosik'][$i])){
        echo '<div class="itemInCart">';
        //echo '<img class="productImg" src="../img/' . $_SESSION['kosik'][$i]['imgLink'] . '">';
        echo '<div class="productName">'.$_SESSION['kosik'][$i]['productName'].'</div>';
        echo '<div class="productPrice">'.$_SESSION['kosik'][$i]['price'].'</div>';
        echo '<div class="productPrice">'.$_SESSION['kosik'][$i]['vat'].'</div>';
        //echo $_SESSION['kosik'][$i]['idProduct'];
        //echo  '<a href="cart.php?&smazat='.$i.'">&#10060</a><br>';
        echo '</div>';
        $sumPrice += $_SESSION['kosik'][$i]['price'];
        //echo $sumPrice;
    }
}
?>

<?php
echo 'Celková cena: '.$sumPrice.',-Kč<br>';
?>

<?php
$_SESSION['kosikPocet'] = 0;
unset($_SESSION['kosik']);
$_SESSION['kosik'] = array();
echo 'OBJEDNANO';
?>
