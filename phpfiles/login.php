<?php
//require_once "../classes/Connection.php";
//$conn = Connection::getPdoInstance();
?>

<?php
//session_start();
if (isset($_POST['submit'])) {
    $email = htmlspecialchars(!empty($_POST['email']) ? trim($_POST['email']) : null);
    $pass1 = htmlspecialchars(!empty($_POST['psw']) ? trim($_POST['psw']) : null);
    $db = new Database();
    if ($db->userExist($email)){
        $u = $db->getUser($email);
        if ($u["password"] === $pass1){
            $_SESSION['uzivatel'] = ($u["idUsers"]) ?? ''; //novejsi kontrolo pro empty(NULL)
            $_SESSION['jmeno'] = ($u["name"]) ?? '';
            $_SESSION['prijmeni'] = ($u["lastName"]) ?? '';
            $_SESSION['prava'] = ($u["privileges"])?? '';
            $_SESSION['email'] = ($u["email"]) ?? '';
            $_SESSION['kosikPocet'] = 0;
            $_SESSION['kosik'] = array();
            echo("přihlášen");
            echo($_SESSION["userName"] . " " . $_SESSION["userPrivileges"]);
            if(isset($_GET['id'])){
                $id = $_GET['id'];
                header('Location:pd.php?&id='.$id);
            }else{
                header('Location: /');
            }
        }else{
            echo("<p class='alert'>špatné heslo</p>");
        }
    }else{
        echo("<p class='alert'>Uživatel se zadaným emailem neexistuje</p>");
    }
}
?>

<link rel="stylesheet" href="../css/login.css">
<div class="log">
    <div class="row">
        <div class="col-75">
            <div class="container">
                <form method="post" action="">
                    <div class="form">
                        <label for="email"><b>Email</b></label>
                        <input type="email" placeholder="Zadej email" name="email" id="email" required><br><br>
                        <label for="psw"><b>Heslo</b></label>
                        <input type="password" placeholder="Zadej heslo" name="psw" id="psw" required><br><br>
                        <button class="btn" name="submit" type="submit" class="login_btn">Přihlásit</button>
                    </div>
                    <div class="form">
                        <p><a href="registration.php">Registrace</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
