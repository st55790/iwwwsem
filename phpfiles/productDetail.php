<head>
    <link rel="stylesheet" href="../css/productDetail.css">
</head>

<body>
<?php

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
            $exist = false;
            foreach ($_SESSION['kosik'] as $tmp) {
                if (strcmp($tmp->getItem()['productName'], $item['productName']) == 0) {
                    $tmp->incrementCount();
                    echo 'aktualni pocet v kosiku: '.$tmp->getCount();
                    echo 'dalsiProdukt';
                    $exist = true;
                }
            }
            if ($exist == false) {
                $product = new Product($item, 0);
                array_push($_SESSION['kosik'], $product);
                //array_push($_SESSION['kosik'], $item);
                //echo $_SESSION['kosikPocet'];
                echo 'prvniProdukt';
                $_SESSION['kosikPocet']++;
            }
            echo 'Produkt byl přidán';
        }

    }
    //echo $_SESSION['kosik'][0]['price'];
}

echo '       </div>
      </div>
';

?>
</body>
