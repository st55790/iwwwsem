<?php
require_once "./classes/Connection.php";
include "classes/Product.php";

//$conn = Connection::getPdoInstance();
    $db = new Database();
    if(!isset($_GET['p'])){

        $result = $db->getAllProducts();

        //$sql = $db->prepare("SELECT * FROM product LIMIT 13");
        //$sql->execute();
        //$result = $sql->fetchAll();
        if($db->getCountProducts() > 0){
            //print_r($result);
            echo '<div class="gallery">';
            foreach($result as $item){
                echo '
            <div>
                <div class="product">
                    <img src="./img/' . $item['imgLink'] . '"/>
                    <h3>'.$item['productName'].'</h3>
                    <h4>'.$item['price'].'.-Kč</h4>
                    <a href="productDetail.php?&id=' . $item['idProduct'] . '" class="buy-button">&#128722</a>
                </div>
            </div>';
                //print_r($item);

            }
            echo '</div>';
        }

    }else{
        $result = $db->getProductsOfCategory();
        //$sql = $db->prepare("SELECT * FROM product LIMIT 13");
        //$sql->execute();
        //$result = $sql->fetchAll();
        if($db->getCountProducts() > 0){
            //print_r($result);
            echo '<div class="gallery">';
            foreach($result as $item){
                if($item['description'] == $_GET['p']){
                    echo '
                    
                        <div class="product">
                            <a href="#"><img src="./img/' . $item['imgLink'] . '"/></a>
                            <h3>'.$item['productName'].'</h3>
                            <h4>'.$item['price'].'.-Kč</h4>
                            <a href="productDetail.php?&id=' . $item['idProduct'] . '" class="buy-button">&#128722</a>
                        </div>
                    ';
                        //print_r($item);

                    }

            }
            echo '</div>';
        }
    }


?>
<head>
    <link rel="stylesheet" href="../css/products.css">
</head>
<?php
?>
