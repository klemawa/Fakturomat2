<?php
session_start();

// czy zalogowany
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// pobranie produktów z bazy 
$conn = new mysqli("localhost", "root", "", "logowanie");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
}

// usuwanie produktu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_product"])) {
    $nazwa_produktu = $_POST["delete_product"];

    $deleteQuery = "DELETE FROM produkty WHERE nazwa_produktu = ?";

    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("s", $nazwa_produktu);

    if ($stmt->execute()) {
        echo "Produkt został usunięty.";
    } else {
        echo "Błąd podczas usuwania produktu: " . $conn->error;
    }

    $stmt->close();
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
            margin-top: 20px;
        }

        input[type="submit"] {
            background-color: #27ae60; 
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #219a52; 
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

    <h2>Lista produktów</h2>
    <table border="1">
        <tr>
            <th>nazwa</th>
            <th>ilość</th>
            <th>cena/szt</th>
            <th>koszt całkowity</th>
            <th>Akcje</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_produktu"] . "</td>";
            echo "<td>" . $row["nazwa_produktu"] . "</td>";
            echo "<td>" . $row["ilość"] . "</td>";
            echo "<td>" . $row["cena"] . "</td>";
            echo "<td><form method='post' action=''>";
            echo "<input type='hidden' name='delete_product' value='" . $row["nazwa_produktu"] . "'>";
            echo "<input type='submit' value='Usuń'></form></td>";
            echo "</tr>";
        }
        ?>
    </table>

    <form action="wystaw_fakture.php" method="post">
        <input type="submit" value="Wystaw fakturę">
    </form>

    <a href="logout.php">Wyloguj</a>

</body>
</html>
