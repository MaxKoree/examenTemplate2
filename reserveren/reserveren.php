<?php

include '../database/database.php';

$conn = mysqli_connect("localhost", "root", "", "restaurantex");

// source table print: https://stackoverflow.com/questions/50254770/how-to-echo-inner-join-in-php

$table = "<table><tr><th>datum</th><th>tijd</th><th>tafel</th><th>naam</th><th>telefoon</th><th>aantal</th></tr>";
$result = $conn->query("SELECT r.datum, r.tijd, r.tafel, k.naam, k.telefoon, r.aantal
FROM reserveringen AS r
INNER JOIN klanten AS k
ON k.ID = r.Klant_ID");
while ($row = $result->fetch_assoc())
{
    $table .= "<tr>";
    $table .= "<td>{$row['datum']}</td>";
    $table .= "<td>{$row['tijd']}</td>";
    $table .= "<td>{$row['tafel']}</td>";
    $table .= "<td>{$row['naam']}</td>";
    $table .= "<td>{$row['telefoon']}</td>";
    $table .= "<td>{$row['aantal']}</td>";
    $table .= "</tr>";
}

$table .= "</table";




$sql = "SELECT naam FROM klanten";

$result = mysqli_query($conn, $sql);
$resultCheck = mysqli_num_rows($result);
$namen = [];

if ($resultCheck > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($namen, $row['naam']);
    }
}

$sql2 = "SELECT datum FROM reserveringen";

$conn2 = mysqli_connect("localhost", "root", "", "excellenttaste");

$result2 = mysqli_query($conn2, $sql2);
$resultCheck2 = mysqli_num_rows($result2);
$datums = [];

if ($resultCheck2 > 0) {
    while ($row = mysqli_fetch_assoc($result2)) {
        array_push($datums, $row['datum']);
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit']) && !empty($_POST['submit'])) {
    $klant = trim(strtolower($_POST['klant']));
    $tafel = trim(strtolower($_POST['tafel']));
    $datum = trim(strtolower($_POST['datum']));
    $tijd = trim(strtolower($_POST['tijd']));
    $aantal = trim(strtolower($_POST['aantal']));
    $reserveringstatus = trim(strtolower($_POST['reserveringstatus']));
    $aantal_k = trim(strtolower($_POST['aantal_k']));
    $allergien = trim(strtolower($_POST['allergien']));
    $opmerkingen = trim(strtolower($_POST['opmerkingen']));
 
    $uCode = mb_substr(uniqid(), 8, 13);

    $db = new database('localhost', 'root', '', 'restaurantex', 'utf8');

    $sql2 = "SELECT ID FROM klanten WHERE Naam = :naam";

    $named_placeholder2 = [
        'naam'=>$klant,
    ];

    $klantencode = $db->select($sql2, $named_placeholder2);


    $date = date("Y-m-d");
    $sql = "INSERT INTO reserveringen VALUES (:ID, :Tafel, :Datum, :Tijd, :Klant_ID, :Aantal, :Reservering_status, :Datum_toegevoegd, :Aantal_k, :allergien, :Opmerkingen)";

        $named_placeholder = [
            'ID'=>$uCode,
            'Tafel'=>$tafel,
            'Datum'=>$datum,
            'Tijd'=>$tijd,
            'Klant_ID'=>$klantencode, 
            'Aantal'=>$aantal, 
            'Reservering_status'=>$reserveringstatus,
            'Datum_toegevoegd'=>$date,
            'Aantal_k'=>$aantal_k,
            'Allergieen'=>$allergien, 
            'Opmerkingen'=>$opmerkingen,
        ];
        var_dump($named_placeholder);
        $db->insert($sql, $named_placeholder, 'reserveren.php');
        
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit2']) && !empty($_POST['submit2'])) {
    $klant = trim(strtolower($_POST['klant']));
    $tafel = trim(strtolower($_POST['tafel']));
    $datum = trim(strtolower($_POST['datum']));
    $tijd = trim(strtolower($_POST['tijd']));
    $aantal = trim(strtolower($_POST['aantal']));
    $reserveringstatus = trim(strtolower($_POST['reserveringstatus']));
    $aantal_k = trim(strtolower($_POST['aantal_k']));
    $allergien = trim(strtolower($_POST['allergien']));
    $opmerkingen = trim(strtolower($_POST['opmerkingen']));
    $datum2 = trim(strtolower($_POST['datum2']));
    $currentDate = date("l jS \of F Y h:i:s A");
    $uCode = mb_substr(uniqid(), 8, 13);

    $db = new database('localhost', 'root', '', 'excellenttaste', 'utf8');

    $currentDate = date("Y-m-d");

    $sql2 = "SELECT klantencode FROM klanten WHERE naam = :naam";

    $named_placeholder2 = [
        'naam'=>$klant,
    ];

    $klantencode = $db->select($sql2, $named_placeholder2);

    $sql = "UPDATE reserveringen SET reserveringcode=:reserveringcode, klantencode=:klantencode, tafel=:tafel, datum=:datum, tijd=:tijd, aantal=:aantal, reserveringstatus=:reserveringstatus, datum_toegevoegd=:datum_toegevoegd, aantal_k=:aantal_k, allergien=:allergien, opmerkingen=:opmerkingen WHERE datum =:datum2;";

    $named_placeholder = [
        'reserveringcode'=>$uCode,
        'klantencode'=>$klantencode[0]['klantencode'], 
        'tafel'=>$tafel,
        'datum'=>$datum,
        'tijd'=>$tijd,
        'aantal'=>$aantal, 
        'reserveringstatus'=>$reserveringstatus,
        'datum_toegevoegd'=>$currentDate,
        'aantal_k'=>$aantal_k,
        'allergien'=>$allergien, 
        'opmerkingen'=>$opmerkingen,
        'datum2'=>$datum2,
    ];

$db->edit_or_delete($sql, $named_placeholder, 'reserveren.php');

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit3']) && !empty($_POST['submit3'])) {

    $db = new database('localhost', 'root', '', 'excellenttaste', 'utf8');

    $sql = "DELETE FROM reserveringen WHERE datum =:datum";

    $datum = trim(strtolower($_POST['datum3']));

    $named_placeholder = [
        'datum'=>$datum,
    ];

$db->edit_or_delete($sql, $named_placeholder, 'reserveren.php');

}
?>

<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
          integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Reserveren</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script src="../script.js"></script>
</head>
<nav class="topnav">
  <a href="hoofdpagina.php">Home</a>
  <a href="reserveren/reserveren.php">Reserveren</a>
  <a href="#about">Gegevens</a>
  <a href="../klanten/klantadd.php">Klant toevoegen</a>
</nav>
<body>
            <form action="reserveren.php" method="post" style="margin: 0 0 3rem 10px;border: 1px solid black; width: 530px; text-align: center; height: 910px; position: absolute">           
                <h1>Reserveren</h1><br>
                

                <label for="klant">Kies klant</label> <br>
                <select id="klant" name="klant">
                    <?php
                    foreach ($namen as $item) {
                        echo "<option value='$item'>$item</option>";
                    } ?>
                </select> <br><br>

                <label for="tafel">tafel</label><br>
                <select id="tafel" name="tafel">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option> <option>10</option>
                    <option>11</option>option>12</option>
                    <option>13</option> <option>14</option> <option>15</option> <option>16</option>
                    <option>17</option><option>18</option> <option>19</option> <option>20</option>
                </select> <br><br>
                <label for="datum">datum</label><br>
                <input id="datum" type="date" name="datum" required /><br><br>
                <label for="tijd">tijd</label><br>
                <input id="tijd" type="time" name="tijd" required /><br><br>
                <label for="aantal">aantal</label><br>
                <select id="aantal" name="aantal">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option>
                </select> <br><br>
                <label for="reserveringstatus">reserveringstatus</label><br>
                <select id="reserveringstatus" name="reserveringstatus">
                    <option>1</option><option>2</option>
                    <option>3</option> <option>4</option>
                </select> <br><br>
                <label for="aantal_k">aantal_k</label><br>
                <select id="aantal_k" name="aantal_k">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option> <option>10</option>
                    <option>11</option>option>12</option>
                    <option>13</option> <option>14</option> <option>15</option> <option>16</option>
                    <option>17</option><option>18</option> <option>19</option> <option>20</option>
                </select> <br><br>
                <label for="allergien">allergiën</label><br>
                <input id="allergien" type="text" name="allergien"  /><br><br>
                <label for="opmerkingen">opmerkingen</label><br>
                <input id="opmerkingen" type="text" name="opmerkingen"  /><br><br>
                <input id="button" type="submit" name="submit" value="toevoegen"/>
                <button style="margin: 30px 0 0 30px" id="" value="submit" onclick="start()">terug</button><br>
            </form>

            <form action="reserveren.php" method="post" style="border: 1px solid black; width: 300px; text-align: center; height: 1100px; float: right;">           
            <h1>Wijzig reservering</h1><br>

            <label for="datum2">Kies datum van reservering</label> <br>
                <select id="datum2" name="datum2">
                    <?php
                    foreach ($datums as $item) {
                        echo "<option value='$item'>$item</option>";
                    } ?>
                </select> <br><br>
                
                <label for="klant">Kies klant</label> <br>
                <select id="klant" name="klant">
                    <?php
                    foreach ($namen as $item) {
                        echo "<option value='$item'>$item</option>";
                    } ?>
                </select> <br><br>

                <label for="tafel">tafel</label><br>
                <select id="tafel" name="tafel">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option> <option>10</option>
                    <option>11</option>option>12</option>
                    <option>13</option> <option>14</option> <option>15</option> <option>16</option>
                    <option>17</option><option>18</option> <option>19</option> <option>20</option>
                </select> <br><br>
                <label for="datum">datum</label><br>
                <input id="datum" type="date" name="datum" required /><br><br>
                <label for="tijd">tijd</label><br>
                <input id="tijd" type="time" name="tijd" required /><br><br>
                <label for="aantal">aantal</label><br>
                <select id="aantal" name="aantal">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option>
                </select> <br><br>
                <label for="reserveringstatus">reserveringstatus</label><br>
                <select id="reserveringstatus" name="reserveringstatus">
                    <option>1</option><option>2</option>
                    <option>3</option> <option>4</option>
                </select> <br><br>
                <label for="aantal_k">aantal_k</label><br>
                <select id="aantal_k" name="aantal_k">
                    <option>1</option>option>2</option>
                    <option>3</option> <option>4</option> <option>5</option> <option>6</option>
                    <option>7</option><option>8</option> <option>9</option> <option>10</option>
                    <option>11</option>option>12</option>
                    <option>13</option> <option>14</option> <option>15</option> <option>16</option>
                    <option>17</option><option>18</option> <option>19</option> <option>20</option>
                </select> <br><br>
                <label for="allergien">allergiën</label><br>
                <input id="allergien" type="text" name="allergien"  /><br><br>
                <label for="opmerkingen">opmerkingen</label><br>
                <input id="opmerkingen" type="text" name="opmerkingen"  /><br><br>
                <input id="button" type="submit" name="submit2" value="Wijzigen"/>
                <button style="margin: 30px 0 0 30px" id="" value="submit" onclick="start()">terug</button><br>
            </form>

            <form action="reserveren.php" method="post" style="border: 1px solid black; width: 300px; text-align: center; height: 240px; margin: 0 0 0 60rem; position: absolute">           
                <h1>Verwijderen</h1><br>
                <label for="datum3">Kies datum van reservering</label> <br>
                <select id="datum3" name="datum3">
                    <?php
                    foreach ($datums as $item) {
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
print $table;
?>