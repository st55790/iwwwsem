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

if(isset($_GET['fname'])){
    $db->updateUser($data['idUser'], $_GET['fname'], $_GET['lname'], $_GET['email'],$_GET['psw']);
    header('Location:/account.php');
}
?>

<?php
    //echo 'your account info<br>';
    echo '<div class="accountPanel">
    <div class="row">
        <div class="col-75">
            <div class="container">
                <form action="#">    
                    <label for="fname">Jméno</label>
                    <input type="text" id="fname" name="fname" value='.$data['firstName'].' required><br><br>
                    <label for="lname">Příjmení</label>
                    <input type="text" id="lname" name="lname" value='.$data['lastName'].' required><br><br>
                    <label for="lname">Email</label>
                    <input type="text" id="email" name="email" value='.$data['email'].' required><br><br>
                    <label for="lname">Heslo</label>
                    <input type="password" id="psw" name="psw" value='.$data['password'].' required><br><br>
                    <a href="#" onclick="myFunction()">&#128065</a><br><br>
                    <input type="submit" value="Potvrdit" class="btn">
                </form>
            </div>            
           </div>
        </div>
    </div>';
?>


