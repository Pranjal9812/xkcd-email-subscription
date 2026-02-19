<?php
$ch = curl_init("https://xkcd.com/info.0.json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo "cURL is still not working!";
} else {
    echo "cURL works! JSON response received.";
}
?>
