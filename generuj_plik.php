<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    //pobranie zaznaczonych produktów z formularza
    $selectedProducts = isset($_POST['product']) ? $_POST['product'] : [];

    // sprawdzenie czycoś zaznaczone
    if (!empty($selectedProducts)) {
        //utworzenie pliku tekstoweg
        $filename = 'faktura.txt';
        $file = fopen($filename, 'w');

        fwrite($file, "\t\t\tFAKTURA\n\n");
        fwrite($file, "Towary:\n");
        fwrite($file, "Id\t\Nazwa\tIlość\tCena/szt\n");

        //zapis zaznaczonych
        foreach ($selectedProducts as $nazwa) {
            // pobranie informacji z bazy danych
            $conn = new mysqli("localhost", "root", "", "logowanie");
            $query = "SELECT * FROM produkty WHERE nazwa_produktu = '$nazwa'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // zapis do pliku
                fwrite($file, $row["id_produktu"] . "\t". "\t" . $row["nazwa_produktu"] . "\t" . $row["ilość"] . "\t". "\t" . $row["cena"] . "\n");
            }
        }
        fwrite($file, "\n\n");
        fwrite($file, "..............\t\t\t\t............\n");
        fwrite($file, "Data i podpis\t\t\t\tData i podpis\n");
        fwrite($file, "wystawiającego\t\t\t\todbierającego");
        // zamknięcie pliku
        fclose($file);

        // ustawienie nagłówków do pobrania pliku
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="faktura.txt"');
        header('Content-Length: ' . filesize($filename));

        // wysłanie zawartości pliku do przeglądarki
        readfile($filename);

        // usunięcie pliku po wysłaniu
        unlink($filename);

        exit();
    } else {
        echo 'Nie wybrano żadnych produktów.';
    }
}
?>

