<?php
session_start();

if (!isset($_SESSION["username"]) || $_SESSION["username"] !== "admin") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["new_username"]) && isset($_POST["new_password"]) && isset($_POST["new_rola"])) {
        $newUsername = $_POST["new_username"];
        $newPassword = $_POST["new_password"];
        $newRola = $_POST["new_rola"];

        $conn = new mysqli("localhost", "root", "", "logowanie");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
        }

        $newUsername = $conn->real_escape_string($newUsername);

        $checkQuery = "SELECT * FROM użytkownicy WHERE username='$newUsername'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            echo "Login już istnieje. Wybierz inny login.";
        } else {
            $insertQuery = "INSERT INTO użytkownicy (username, password, rola_uzytkownika) VALUES ('$newUsername', '$newPassword', '$newRola')";
            if ($conn->query($insertQuery) === TRUE) {
                echo "Nowy pracownik został dodany!";
            } else {
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }

        $conn->close();
    }
}

if (isset($_POST["delete_username"])) {
    $deleteUsername = $_POST["delete_username"];

    $conn = new mysqli("localhost", "root", "", "logowanie");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
    }

    $deleteQuery = "DELETE FROM użytkownicy WHERE username='$deleteUsername'";
    if ($conn->query($deleteQuery) === TRUE) {
        echo "Pracownik został usunięty!";
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }

    $conn->close();
}

$conn = new mysqli("localhost", "root", "", "logowanie");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
}

$query = "SELECT * FROM użytkownicy";
$result = $conn->query($query);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Nowego Pracownika</title>
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

        form {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ecf0f1;
            border-radius: 4px;
            margin-bottom: 10px;
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

    <h2>Dodaj Nowego Pracownika</h2>

    <form method="post" action="">
        <label for="new_id">Nowe id:</label>
        <input type="new_id" name="new_username" required>

        <label for="new_username">Nowy login:</label>
        <input type="id_uzytkownika" name="new_username" required>

        <label for="new_password">Nowe hasło:</label>
        <input type="password" name="new_password" required>

        <label for="new_rola">Podaj role 0/1:</label>
        <input type="rola_uzytkownika" name="new_rola" required>

        <input type="submit" value="Dodaj pracownika">
    </form>

    <a href="welcome.php">Powrót do strony powitalnej</a>

    <h2>Lista pracowników</h2>
    <table>
        <tr>
            <th>Id użytkownika</th>
            <th>Login</th>
            <th>Hasło</th>
            <th>Rola</th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_uzytkownika"] . "</td>";
            echo "<td>" . $row["username"] . "</td>";
            echo "<td>" . $row["password"] . "</td>";
            echo "<td>" . $row["rola_uzytkownika"] . "</td>";
            echo "<td><form method='post' action=''>";
            echo "<input type='hidden' name='delete_username' value='" . $row["username"] . "'>";
            echo "<input type='submit' value='Usuń'></form></td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>
