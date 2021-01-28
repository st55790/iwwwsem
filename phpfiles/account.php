<?php
if(!isset($_SESSION['prava'])){
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
$db = new Database();
$data = $db->getUser($_SESSION['email']);

if (isset($_POST['submit'])) {
    $fname = htmlspecialchars(!empty($_POST['fname']) ? trim($_POST['fname']) : NULL);
    $lname = htmlspecialchars(!empty($_POST['lname']) ? trim($_POST['lname']) : NULL);
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);

    if (isset($_SESSION['prava'])) {
        $db->updateUser($data['idUser'], $fname, $lname, $email, $pass);
        header('Location:/account.php');
    }
}
//if(isset($_GET['fname'])){
//    $db->updateUser($data['idUser'], $_GET['fname'], $_GET['lname'], $_GET['email'],$_GET['psw']);
//    header('Location:/account.php');
//}
?>

<?php
//echo 'your account info<br>';
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
        <a href="account.php?&showOrders"><button class="btn" name="showOrders" type="submit" class="login_btn">Zobraz moje objednávky</button></a>';


if (isset($_GET['showOrders'])) {
    if ($db->orderExist($data['idUser'])) {
        $orders = $db->getUserOrders($data['idUser']);
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
                <td><a href="account.php?&' . $order['idOrder'] . '">Zobrazit objednávku</a></td>    
            </tr>';

            $i++;
        }
        echo '</table>';
    } else {
        echo 'Nemáš ještě žádné objednávky';
    }
}
echo '</div>';

?>


