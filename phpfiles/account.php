<?php
if (!isset($_SESSION['prava'])) {
    header('Location:/');
}
?>

<link rel="stylesheet" href="../css/payment.css">
<script>
    function myFunction() {
        var x = document.getElementById("psw");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
<?php
$dbUser = new UserDB();
$data = $dbUser->getUser($_SESSION['email']);

if (isset($_POST['submit'])) {
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : NULL);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : NULL);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);

    if (isset($_SESSION['prava'])) {
        $dbUser->updateUser($data['idUser'], $fname, $lname, $email, $pass);
        header('Location:/account.php');
    }
}


?>

<?php
echo '<div class="accountPanel">
    <div class="row">
        <div class="col-75">
            <div class="container">
                <form method="post" action="">    
                    <label for="fname">Jméno</label>
                    <input type="text" id="fname" name="fname" value=' . $data['firstName'] . ' required><br>
                    <label for="lname">Příjmení</label>
                    <input type="text" id="lname" name="lname" value=' . $data['lastName'] . ' required><br>
                    <label for="lname">Email</label>
                    <input type="text" id="email" name="email" value=' . $data['email'] . ' required><br>
                    <label for="lname">Heslo</label>
                    <input type="password" id="psw" name="psw" value=' . $data['password'] . ' required><br>
                    <a class="pswEye" href="#" onclick="myFunction()">&#128065</a><br><br>
                    
                    <button class="btn" name="submit" type="submit" class="login_btn">Potvrdit</button>
                    
                </form>            
            </div>            
           </div>
        </div>
        <br>
        <a href="account.php?showOrders"><button class="btn" name="showOrders" type="submit" class="login_btn">Zobraz moje objednávky</button></a>';


if (isset($_GET['showOrders'])) {
    $dbOrder = new OrderDB();
    if ($dbOrder->orderExist($data['idUser'])) {
        $orders = $dbOrder->getUserOrders($data['idUser']);
        echo '<br><br>
        <table>
            <thead>
                <th>Číslo</th>
                <th>Datum</th>
                <th>Zobraz</th>
            </thead>';
        $i = 1;
        foreach ($orders as $order) {
            echo '<tr>
                <td>' . $i . '. objednávka</td>
                <td>' . $order['timeOrder'] . '</td>
                <td><a href="account.php?order=' . $order['idOrder'] . '">Zobrazit objednávku</a></td>    
            </tr>';

            $i++;
        }
        echo '</table>';
    } else {
        echo 'Nemáš ještě žádné objednávky';
    }
}
echo '</div>';

if (isset($_GET['order'])) {
    $idOrder = $_GET['order'];

    echo '<div id="order">';
    $dbInvoice = new InvoiceDB();
    $dbProduct = new ProductDB();
    $db = new Database();
    $invoice = $dbInvoice->getInvoiceByOrderId($idOrder);
    $products = $db->getAllProductFromOrderByIdOrder($idOrder);

    $sum = 0;

    echo '<table>
            <thead>
                <th>Jméno produktu</th>
                <th>Počet</th>
                <th>Cena za kus v kč</th>
                <th>DPH</th>
            </thead>';

    foreach ($products as $item) {
        $product = $dbProduct->getProductById($item['Product_idProduct']);
        echo '<tr><td>' . $product['productName']. '</td>';
        echo '<td>' . $item['quantity']. '</td>';
        echo '<td>' . $product['price']. '</td>';
        echo '<td>' . $product['vat']. '</td>';

        echo '</tr><br>';
        $sum += $product['price'] * $item['quantity'];
    }

    echo 'Celkova cena objednávky: ' . $sum;
    echo '</table></div>';
}

?>


