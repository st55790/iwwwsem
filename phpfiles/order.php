<?php
if (!isset($_SESSION['prava'])) {
    header('Location:/');
}
?>
<link rel="stylesheet" href="../css/order.css">
<?php

if (isset($_POST['submit'])) {
    $fname = htmlspecialchars(!empty($_POST['fullname']) ? trim($_POST['fullname']) : NULL);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : NULL);
    $adr = htmlspecialchars(!empty($_POST['address']) ? trim($_POST['address']) : null);
    $city = htmlspecialchars(!empty($_POST['city']) ? trim($_POST['city']) : null);
    $mob = htmlspecialchars(!empty($_POST['mob']) ? trim($_POST['mob']) : null);
    $zip = htmlspecialchars(!empty($_POST['zip']) ? trim($_POST['zip']) : null);

    echo '<div class="invoice">';
    echo '<div class="invoiceInfo"><div class="seller">Prodejce<br>';
    echo 'Cele jmeno: Tomáš Prudký<br>';
    echo 'Email: eshop@eshopprudky.com<br>';
    echo 'Adresa: Nádražní 518<br>';
    echo 'Město: České Budějovice<br>';
    echo 'Telefon: 111222333<br>';
    echo 'PSČ: 12345<br></div>';

    echo '<div class="billingAddress">Fakturační adresa<br>';
    echo 'Cele jmeno: ' . $fname .'<br>';
    echo 'Email: ' . $email.'<br>';
    echo 'Adresa: ' . $adr.'<br>';
    echo 'Město: ' . $city.'<br>';
    echo 'Stát: ' . $mob.'<br>';
    echo 'PSČ: ' . $zip . '<br></div></div>';
}

echo '<table>
<thead>
    <th>Jméno produktu</th>
    <th>Počet</th>
    <th>Cena za kus v kč</th>
    <th>DPH</th>
</thead>';
$sumPrice = 0;
for ($i = 0; $i < $_SESSION['kosikPocet']; $i++) {
    if ($_SESSION['kosik'][$i]->getCount() > 0) {
        $item = $_SESSION['kosik'][$i]->getItem();
        $count = $_SESSION['kosik'][$i]->getCount();
        echo '<div class="itemInCart"><tr>';
        //echo '<img class="productImg" src="../img/' . $item['imgLink'] . '">';
        echo '<td>' . $item['productName'] . '</td>';
        echo '<td>' . $count . '</td>';
        echo '<td>' . $item['vat'] . '</td>';
        echo '<td>' . $item['price'] . '</td>';
        //echo '<a href="cart.php?&add=' . $i . '">&#10133</a>';
        //echo '<a href="cart.php?&smazat=' . $i . '">&#10134</a>';
        echo '</tr></div>';
        $sumPrice += $item['price'] * $count;
        //echo $sumPrice;

    }
}
echo '</table>'
?>

<?php
echo '<div class="summary">Celková cena: ' . $sumPrice . ',-Kč<br></div>';
echo '</div>'
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
?>

