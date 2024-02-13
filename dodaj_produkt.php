<?php
session_start();

// Sprawdź czy admin
if (!isset($_SESSION["username"]) || $_SESSION["username"] !== "admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $id_produktu = $_POST["id_produktu"];
    $nazwa_produktu = $_POST["nazwa_produktu"];
    $ilość = $_POST["ilość"];
    $cena = $_POST["cena"];

    $conn = new mysqli("localhost", "root", "", "logowanie");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
    }

    $id_produktu = $conn->real_escape_string($id_produktu);
    $nazwa_produktu = $conn->real_escape_string($nazwa_produktu);
    $ilość = $conn->real_escape_string($ilość);
    $cena = $conn->real_escape_string($cena);

    $query = "INSERT INTO produkty (id_produktu, nazwa_produktu, ilość, cena) VALUES ('$id_produktu', '$nazwa_produktu', '$ilość', '$cena')";

    if ($conn->query($query)) {
        echo "Produkt dodany pomyślnie.";
    } else {
        error_log("Błąd podczas dodawania produktu: " . mysqli_error($conn));
        echo "Wystąpił błąd podczas dodawania produktu. Spróbuj ponownie.";
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

        form {
            width: 300px;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
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

    <h2>Dodaj Nowy Produkt</h2>

    <form method="post" action="">
        <label for="id">Id produktu:</label>
        <input type="number" name="id_produktu" required>

        <label for="nazwa">Nazwa:</label>
        <input type="text" name="nazwa_produktu" required>

        <label for="ilosc">Ilość:</label>
        <input type="number" name="ilość" required>

        <label for="cena">Cena/szt:</label>
        <input type="number" name="cena" required>



        <input type="submit" name="submit" value="Dodaj Produkt">
    </form>
    <a href="welcome.php" class="button">Powrót</a>
</body>
</html>
