<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Strona administratora</title>
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

        button {
            background-color: #27ae60;
            color: #fff;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #219a52;
        }

        a {
            color: #27ae60;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Witaj, <?php echo $username; ?>!</h2>

    <button onclick="window.location.href='wystawienie_faktury.php'">Wystaw fakturę</button>
    <button onclick="window.location.href='dodaj_produkt.php'">Dodaj produkt</button>
    <button onclick="window.location.href='dodanie_pracownika.php'">Dodaj nowego pracownika</button>
    <button onclick="window.location.href='zamowienie.php'">Zamównienie</button>
    <a href="logout.php">Wyloguj</a>

</body>
</html>
