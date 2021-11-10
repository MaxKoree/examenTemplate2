<?php

include '../database/database.php';

$conn = mysqli_connect("localhost", "root", "", "restaurantex");


// De Tabel voorbereiden:


$table = "<table><tr><th>naam</th><th>telefoon</th><th>email</th></tr>";
$result = $conn->query("SELECT Naam, Telefoon, Email FROM `klanten` WHERE 1");
while ($row = $result->fetch_assoc())
{
    $table .= "<tr>";
    $table .= "<td>{$row['Naam']}</td>";
    $table .= "<td>{$row['Telefoon']}</td>";
    $table .= "<td>{$row['Email']}</td>";

    $table .= "</tr>";
}

$table .= "</table";

// Einde tabel voorbereiden:
 

//dit is de dropdown voor klanten naam

$sql = "SELECT naam FROM klanten";

$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);
$namen = [];

if ($resultCheck > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($namen, $row['naam']);
    }
}
//einde dropdown


//klanten toevoegen: maakt gebruik van eerste form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])) {
    $email = trim(strtolower($_POST['email']));
    $klant = trim(strtolower($_POST['klant']));
    $telefoon = trim(strtolower($_POST['telefoon']));

    $uCode= mb_substr(uniqid(), 8, 13);

    $db = new database('localhost', 'root', '', 'restaurantex', 'utf8');

    $sql = "INSERT INTO klanten VALUES (:ID, :Naam, :Telefoon, :Email)";

        $named_placeholder = [
            'ID'=>$uCode, 
            'Naam'=>$klant,
            'Telefoon'=>$telefoon,
            'Email'=>$email,
        ];

        $db->insert($sql, $named_placeholder, 'klantadd.php');
}

//klanten wijzigen: maakt gebruik van tweede form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit2']) && !empty($_POST['submit2'])) {

    $uCode= mb_substr(uniqid(), 8, 13);

    $db = new database('localhost', 'root', '', 'restaurantex', 'utf8');
    $sql = "UPDATE klanten SET ID=:ID, Naam=:naam, Telefoon=:telefoon, Email=:email WHERE Naam =:naam2;";

    $email2 = trim(strtolower($_POST['email2']));
    $klant2 = trim(strtolower($_POST['klant2']));
    $telefoon2 = trim(strtolower($_POST['telefoon2']));
    $naam2 = trim(strtolower($_POST['naam2']));

    $named_placeholder = [
        'ID'=>$uCode, 
        'naam'=>$klant2,
        'telefoon'=>$telefoon2,
        'email'=>$email2,
        'naam2'=>$naam2,
    ];

$db->edit_or_delete($sql, $named_placeholder, 'klantadd.php');
}

//klanten verwijderen: maakt gebruik van derde form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit3']) && !empty($_POST['submit3'])) {

    $uCode= mb_substr(uniqid(), 8, 13);

    $db = new database('localhost', 'root', '', 'restaurantex', 'utf8');

    $sql = "DELETE FROM klanten WHERE naam =:naam";

    $naam3 = trim(strtolower($_POST['naam3']));

    $named_placeholder = [
        'naam'=>$naam3,
    ];

$db->edit_or_delete($sql, $named_placeholder, 'klantadd.php');

}
?>

<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Klanten</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../script.js"></script>
</head>
<nav class="topnav">
  <a href="hoofdpagina.php">Home</a>
  <a href="../reserveren/reserveren.php">Reserveren</a>
  <a href="#about">Gegevens</a>
  <a href="klantadd.php">Klant toevoegen</a>
</nav>
<body>
            <form action="klantadd.php" method="post" style="margin: 0 0 3rem 0;border: 1px solid black; width: 230px; text-align: center; height: 430px; position: fixed">           
                <h1>Toevoegen</h1><br>
                <label for="klant">Klantnaam</label><br>
                <input id="klant" type="text" name="klant" required /><br><br>
                <label for="email">email</label><br>
                <input id="email" type="email" name="email" required /><br><br>
                <label for="telefoon">telefoon</label><br>
                <input id="telefoon" type="text" name="telefoon" required /><br>
                <input id="button" type="submit" name="submit" value="toevoegen"/>
                <button style="margin: 30px 0 0 30px" id="" value="submit" onclick="goMenu()">terug</button><br>
            </form>
            <form action="klantadd.php" method="post" style="border: 1px solid black; width: 300px; text-align: center; height: 470px; float: right;">           
                <h1>Wijzigen</h1><br>
                <label for="naam">Kies welke klant je wilt wijzigen:</label> <br>
                <select id="naam" name="naam2">
                    <?php
                    foreach ($namen as $item) {
                        echo "<option value='$item'>$item</option>";
                    } ?>
                </select> <br><br>
                <label for="klant">Klantnaam</label><br>
                <input id="klant" type="text" name="klant2" required /><br><br>
                <label for="email">email</label><br>
                <input id="email" type="email" name="email2" required /><br><br>
                <label for="telefoon">telefoon</label><br>
                <input id="telefoon" type="text" name="telefoon2" required /><br>
                <input id="button" type="submit" name="submit2" value="wijzigen"/>
                <button style="margin: 30px 0 0 30px" id="" value="submit" onclick="goMenu()">terug</button><br>
            </form>

            <form action="klantadd.php" method="post" style="border: 1px solid black; width: 300px; text-align: center; height: 250px; float: right; margin: 0 0 0 20rem; position: absolute">           
                <h1>Verwijderen</h1><br>
                <label for="naam3">Kies welke klant je wilt verwijderen:</label> <br>
                <select id="naam3" name="naam3">
                    <?php
                    foreach ($namen as $item) {
                        echo "<option value='$item'>$item</option>";
                    } ?>
                </select> <br><br>
          
                <input id="button" type="submit" name="submit3" value="Verwijderen"/>
                <a style="margin: 30px 0 0 30px" id="" value="submit" href="./welcome.php">terug</a><br>
            </form>
            <script type="text/javascript" src="../script.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV"
        crossorigin="anonymous"></script>
</body>
<style>
table {
    margin: 27rem 0 0 45%;
    width: 36%;
}
</style>
</html>
<?php 


/**
 * 
 * De Tabel op het scherm plaatsen:
 * 
 */

print $table;
?>