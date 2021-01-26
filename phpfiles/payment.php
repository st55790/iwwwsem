<link rel="stylesheet" href="../css/payment.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="payment">
    <div class="row">
        <div class="col-75">
            <div class="container">
                <form action="order.php">

                    <div class="row">
                        <div class="col-50">
                            <h3>Fakturační adresa</h3>
                            <label for="fname"><i class="fa fa-user"></i> Celé jméno</label>
                            <input type="text" id="fname" name="firstname" placeholder="Jan Novák" required>
                            <label for="email"><i class="fa fa-envelope"></i> Email</label>
                            <input type="text" id="email" name="email" placeholder="jannovak@email.com" required>
                            <label for="adr"><i class="fa fa-address-card-o"></i> Adresa</label>
                            <input type="text" id="adr" name="address" placeholder="Zahradní 518" required>
                            <label for="city"><i class="fa fa-institution"></i> Město</label>
                            <input type="text" id="city" name="city" placeholder="Brno" required>

                            <div class="row">
                                <div class="col-50">
                                    <label for="state">Stát</label>
                                    <input type="text" id="state" name="state" placeholder="Czech Republic" required>
                                </div>
                                <div class="col-50">
                                    <label for="zip">PSČ</label>
                                    <input type="text" id="zip" name="zip" placeholder="62000" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-50">
                            <h3>Payment</h3>
                            <label for="fname">Příjmané karty</label>
                            <div class="icon-container">
                                <i class="fa fa-cc-visa" style="color:navy;"></i>
                                <i class="fa fa-cc-mastercard" style="color:red;"></i>
                                <i class="fa fa-cc-paypal" style="color:blue;"></i>
                                <i class="fa fa-google-wallet" style="color:orange;"></i>
                            </div>
                            <label for="cname">Jméno na kartě</label>
                            <input type="text" id="cname" name="cardname" placeholder="Jan Novák" required>
                            <label for="ccnum">Číslo kreditní karty</label>
                            <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444" required>
                            <label for="expmonth">Měsíc expirace</label>
                            <input type="text" id="expmonth" name="expmonth" placeholder="Prosinec" required>
                            <div class="row">
                                <div class="col-50">
                                    <label for="expyear">Rok expirace</label>
                                    <input type="text" id="expyear" name="expyear" placeholder="2024" required>
                                </div>
                                <div class="col-50">
                                    <label for="cvv">CVV</label>
                                    <input type="text" id="cvv" name="cvv" placeholder="352" required>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input type="submit" value="Pokračuj v platbě" class="btn">
                </form>
            </div>
        </div>
    </div>
</div>