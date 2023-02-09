<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Carbon\Carbon;

require_once 'vendor/autoload.php';

$now = Carbon::now('Europe/Prague');
$client = new Client([ 'base_uri' => 'https://date.nager.at/api/v3/' ]);

?>

<!doctype html>
<html lang="cs">
<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-GDFLE2W4ND"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-GDFLE2W4ND');
    </script>

    <title>Je dnes svátek?</title>
    <link rel="stylesheet" href="style.css" />
    <meta charset="UTF-8">
    <meta name="keywords" content="státní svátky, česká republika, kalendář svátků, pracovní volno, slavnosti, sváteční dny">
    <meta name="author" content="Filip Kocháň">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Zde lze zjistit, zda je dnes v České republice státní svátek.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand&family=Tiro+Devanagari+Hindi&display=swap" rel="stylesheet">
<body>
<main>
<?php
    try {
        $year = $now->get('year');
        $response = $client->get("publicholidays/$year/CZ");
        $holidays = json_decode($response->getBody());

        $date = $now->format('Y-m-d');
        $result = null;
        foreach ($holidays as $holiday) {
            if ($holiday->date === $date) {
                $result = $holiday;
            }
        }

        if ($result) {
            echo "<h1>Ano,</h1>";
            if (($name = $result->localName ?? $result->name)) {
                echo "<p>dnes je <a href='https://duckduckgo.com?q="
                    .urlencode($name)
                    ."'>$name</a>.</p>";
            }
        } else {
            echo "<h1>Ne.</h1>";
            echo "<p>Dnes není žádný svátek.</p>";
        }
    } catch (GuzzleException $e) {
        echo "<h1>Možná, nevíme&mldr;</h1>";
        echo "<p>Bohužel někde nastala chyba :(</p>";
    }
?>
</main>
<!-- This site was created by Filip Kochan, 2023. -->
<!-- If you have any suggestions, don't hesitate to contact me at filda<dot>koch<at>gmail<dot>com. -->
<!-- The source code can be found at https://github.com/FilipKochan/jednessvatek. -->
</body>
</html>