<?php

    $dbProduct = new ProductDB();
    if(!isset($_GET['p'])){

        $result = $dbProduct->getAllProducts();

        if($dbProduct->getCountProducts() > 0){
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

            }
            echo '</div>';
        }

    }else{
        $result = $dbProduct->getProductsOfCategory();
        if($dbProduct->getCountProducts() > 0){
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
