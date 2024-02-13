<?php
session_start();

// Sprawdź czy admin
if (!isset($_SESSION["username"]) || $_SESSION["username"] !== "admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $id_zamowienia = $_POST["id_zamowienia"];
    $id_produktu = $_POST["id_produktu"];
    $id_uzytkownika = $_POST["id_uzytkownika"];
    $ilość_produktu = $_POST["ilość_produktu"];

    $conn = new mysqli("localhost", "root", "", "logowanie");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
    }

    $id_zamowienia = $conn->real_escape_string($id_zamowienia);
    $id_produktu = $conn->real_escape_string($id_produktu);
    $id_uzytkownika= $conn->real_escape_string($id_uzytkownika);
    $ilość_produktu = $conn->real_escape_string($ilość_produktu);

    $query = "INSERT INTO zamówienia (id_zamowienia, id_produktu, id_uzytkownika, ilość_produktu) VALUES ('$id_zamowienia', '$id_produktu', '$id_uzytkownika', '$ilość_produktu')";

    if ($conn->query($query)) {
        echo "Zamówienie złożone pomyślnie.";
    } else {
        error_log("Błąd podczas zamawiania produktu: " . mysqli_error($conn));
        echo "Wystąpił błąd podczas zamawiania produktu. Spróbuj ponownie.";
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Produkt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
            color: #ecf0f1;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 80%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ecf0f1; 
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #27ae60; 
            color: #fff; 
        }

        form {
            width: 300px;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #ecf0f1;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #3498db;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #27ae60;
            color: #ecf0f1;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        a {
            color: #27ae60;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Lista Produktów</h2>
    <table border="1">
        <tr>
            <th>id</th>
            <th>nazwa</th>
            <th>ilość</th>
            <th>cena/szt</th>
        </tr>
        <?php
        $conn = new mysqli("localhost", "root", "", "logowanie");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
        }

        $query = "SELECT * FROM produkty";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_produktu"] . "</td>";
            echo "<td>" . $row["nazwa_produktu"] . "</td>";
            echo "<td>" . $row["ilość"] . "</td>";
            echo "<td>" . $row["cena"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Dodaj Produkt do Zamówienia</h2>
    <form method="post" action="">
        <label for="id">Id zamówienia:</label>
        <input type="number" name="id_zamowienia" required>

        <label for="nazwa">Id produktu:</label>
        <input type="text" name="id_produktu" required>

        <label for="ilosc">Id użytkownika:</label>
        <input type="number" name="id_uzytkownika" required>

        <label for="cena">Ilość:</label>
        <input type="number" name="ilość_produktu" required>

        <input type="submit" name="submit" value="Dodaj Zamówienie">
    </form>
    <a href="welcome.php" class="button">Powrót</a>
</body>
</html>
