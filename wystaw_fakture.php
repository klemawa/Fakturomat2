<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// pobranie produktów z bazy danych
$conn = new mysqli("localhost", "root", "", "logowanie");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
}

$query = "SELECT * FROM produkty";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wystawienie faktury</title>
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

        th,
        td {
            border: 1px solid #ecf0f1;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #27ae60;
            color: #fff;
        }

        input[type="checkbox"] {
            margin-top: 2px;
        }

        input[type="submit"] {
            background-color: #27ae60;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: #219a52;
        }
    </style>
</head>

<body>

    <h2>Lista produktów</h2>
    <form method="post" action="generuj_plik.php">
        <table border="1">
            <tr>
                <th>id</th>
                <th>nazwa</th>
                <th>ilość</th>
                <th>cena/szt</th>
                <th>zaznacz</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id_produktu"] . "</td>";
                echo "<td>" . $row["nazwa_produktu"] . "</td>";
                echo "<td>" . $row["ilość"] . "</td>";
                echo "<td>" . $row["cena"] . "</td>";
                echo "<td><input type='checkbox' name='product[]' value='" . $row["nazwa_produktu"] . "'></td>";
                echo "</tr>";
            }
            ?>
        </table>

        <input type="submit" name="submit" value="Zapisz zaznaczone produkty">
    </form>

</body>

</html>
