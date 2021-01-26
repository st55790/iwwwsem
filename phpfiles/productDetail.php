<head>
    <link rel="stylesheet" href="../css/productDetail.css">
</head>

<body>
<?php
include "classes/Product.php";

$db = new Database();
$item = $db->getProduct($_GET['id']);


echo '<div class="gallery">
        <div class="product"> 
            <img src="./img/' . $item['imgLink'] . '"/>
            <h3>' . $item['productName'] . '</h3>
            <h4>' . $item['price'] . '.-Kč</h4>
            <p>' . $item['productDescription'] . '</p>';
if (!isset($_SESSION['prava'])) {
    echo '<a href="login.php?&id=' . $_GET['id'] . '"><div class="buy-button">&#128722</div></a>';
} else {
    echo '<a href="productDetail.php?&id=' . $_GET['id'] . '&koupit"><div class="buy-button">&#128722</div></a>';
    if (isset($_GET['koupit'])) {
        if (isset($_SESSION['prava'])) {

            //if(in_array($item, $_SESSION['kosik'])){
            //    echo 'existuje';
            //}else{
            //    $count = 0;
            //    $product = new Product($item, $count);
            //   array_push($_SESSION['kosik'], $product);
            //    $_SESSION['kosikPocet']++;
            //    //echo $_SESSION['kosikPocet'];
            //    echo 'Produkt byl přidán';
            //    $pd = $_SESSION['kosik'][0];
            //    echo $pd->getCount();
            //}
            array_push($_SESSION['kosik'], $item);
            $_SESSION['kosikPocet']++;
            //echo $_SESSION['kosikPocet'];
            echo 'Produkt byl přidán';
            //$pd = $_SESSION['kosik'][0];
            //print_r(array_column($_SESSION['kosik'][0], 'count'));
        }

    }
    //echo $_SESSION['kosik'][0]['price'];
}

echo '       </div>
      </div>
';

?>
</body>
