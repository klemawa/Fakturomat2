<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // łączenie z bazą
    $conn = new mysqli("localhost", "root", "", "logowanie");

    // czy poprawne
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_errno . ": " . $conn->connect_error);
    }

    $username = $conn->real_escape_string($username);

    $query = "SELECT * FROM użytkownicy WHERE username=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);

    // wykonanie zapytania
    $stmt->execute();

    // pobranie wyników
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // weryfikacja hasła
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            // poprawne logowanie
            $_SESSION["username"] = $username;

            // przekierowanie do odpowiedniej strony
            if ($username === 'admin') {
                header("Location: welcome.php");
            } else {
                header("Location: welcome2.php");
            }

            exit();
        } else {
            // błędne hasło
            $error = "Błędne dane logowania";
        }
    } else {
        // brak użytkownika
        $error = "Błędne dane logowania";
    }

    // zamknięcie połączenia z bazą danych
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c3e50; /* ciemnozielone tło */
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            flex-direction: column;
            text-align: center;
            color: #ecf0f1; /* kolor tekstu */
        }

        h2 {
            margin-bottom: 20px;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #ecf0f1; /* kolor etykiety */
        }

        input {
            padding: 10px;
            margin-bottom: 15px;
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #27ae60; /* zmieniony kolor obramowania pól input */
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #27ae60; /* ciemnozielony kolor przycisku */
            color: #fff; /* biały kolor tekstu przycisku */
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #219a52; /* ciemniejszy odcień ciemnozielonego po najechaniu myszką */
        }

        p {
            color: red; /* czerwony kolor komunikatu o błędzie */
        }
    </style>
</head>
<body>

    <h2>Logowanie</h2>

    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>

    <form method="post" action="">
        <label for="username">Login:</label>
        <input type="text" name="username" required>

        <label for="password">Hasło:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Zaloguj">
    </form>

</body>
</html>