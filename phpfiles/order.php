<?php

if (isset($_POST['submit'])) {
    $fname = htmlspecialchars(!empty($_POST['fullname']) ? trim($_POST['fullname']) : NULL);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : NULL);
    $adr = htmlspecialchars(!empty($_POST['address']) ? trim($_POST['address']) : null);
    $city = htmlspecialchars(!empty($_POST['city']) ? trim($_POST['city']) : null);
    $mob = htmlspecialchars(!empty($_POST['mob']) ? trim($_POST['mob']) : null);
    $zip = htmlspecialchars(!empty($_POST['zip']) ? trim($_POST['zip']) : null);

    echo 'Cele jmeno: ' . $fname;
    echo 'Email: ' . $email;
    echo 'Adresa: ' . $adr;
    echo 'Město: ' . $city;
    echo 'Stát: ' . $mob;
    echo 'PSČ: ' . $zip;
}


$sumPrice = 0;
for ($i = 0; $i < $_SESSION['kosikPocet']; $i++) {
    if ($_SESSION['kosik'][$i]->getCount() > 0) {
        $item = $_SESSION['kosik'][$i]->getItem();
        $count = $_SESSION['kosik'][$i]->getCount();
        echo '<div class="itemInCart">';
        //echo '<img class="productImg" src="../img/' . $item['imgLink'] . '">';
        echo '<div class="productName">' . $item['productName'] . '</div>';
        echo '<div class="productPrice">' . $item['price'] . '</div>';
        echo '<div class="productCount">' . $count . '</div>';
        //echo '<a href="cart.php?&add=' . $i . '">&#10133</a>';
        //echo '<a href="cart.php?&smazat=' . $i . '">&#10134</a>';
        echo '</div>';
        $sumPrice += $item['price'] * $count;
        //echo $sumPrice;

    }
}
?>

<?php
echo 'Celková cena: ' . $sumPrice . ',-Kč<br>';
?>

<?php

//ORDER
$db = new Database();
$idUser = ($db->getUser($_SESSION['email'])['idUser']);
$datetime = new DateTime();
$time = $datetime->format('Y-m-d H:i:s');
$db->insertOrder($idUser, $time);

//INVOICE
$orderId = $db->getOrder($idUser, $time);
$db->insertInvoice($mob, $city, $zip, $orderId['idOrder']);

//ORDER_HAS_PRODUCT
for ($i = 0; $i < $_SESSION['kosikPocet']; $i++) {
    if ($_SESSION['kosik'][$i]->getCount() > 0) {
        $item = $_SESSION['kosik'][$i];
        $db->insertOrderHasProduct($orderId['idOrder'], $item->getItem()['idProduct'], $item->getItem()['price'], $item->getCount());
    }
}
//

$_SESSION['kosikPocet'] = 0;
unset($_SESSION['kosik']);
$_SESSION['kosik'] = array();
echo 'OBJEDNANO';
?>

