<?php
//require_once "./classes/Connection.php";
//include 'phpfiles/header.php';

//$_SESSION["cart"]["1"]["quantity"] = 1;
//$_SESSION["cart"]["2"]["quantity"] = 2;

function getBy($atr, $value, $array){
    foreach($array as $key => $val){
        if($val[$atr] == $value){
            return $key;
        }
    }
    return null;
}

//function addToCart($id){
//    if(array_key_exists($id, $_SESSION["cart"])){
//        $_SESSION["cart"][$id]["quantity"] = 1;
//    }else{
//        $_SESSION["cart"][$id]["quantity"]++;
//    }
//}

?>

<head>
    <link rel="stylesheet" href="../css/productDetail.css">
</head>

<body>
<?php
$db = new Database();
$item = $db->getProduct($_GET['id']);


echo '<div class="gallery">
        <div class="product"> 
            <a href="./img/' . $item['imgLink'] . '"><img src="./img/' . $item['imgLink'] . '"/></a>
            <h3>'.$item['productName'].'</h3>
            <h4>'.$item['price'].'.-Kč</h4>
            <p>'.$item['productDescription'].'</p>';
if(!isset($_SESSION['prava'])){
    echo '<a href="login.php?&id='.$_GET['id'].'"><div class="buy-button">&#128722</div></a>';
}else{
    echo '<a href="pd.php?&id='.$_GET['id'].'&koupit"><div class="buy-button">&#128722</div></a>';
    if(isset($_GET['koupit'])){
        if(!isset($_SESSION['prava'])){

        }else{
            array_push($_SESSION['kosik'], $item);
            $_SESSION['kosikPocet']++;
            //echo $_SESSION['kosikPocet'];
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
