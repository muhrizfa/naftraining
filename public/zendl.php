<?php

/* set url and path */
$url = "https://git.neuron.id/pub/zend/-/archive/master/zend-master.zip";
$venPath = __DIR__ . '/../vendor';
$tmpPath = __DIR__ . '/../temp';
$neuPath = $venPath . '/Neuron';
$zipFile = "{$tmpPath}/zend-master.zip";

/* no limit */
set_time_limit(0);

/* already exists */
if (file_exists("{$venPath}/Zend")) die("OK");

/* mkdir temp */
if (!file_exists($tmpPath)) {
    if (!@mkdir($tmpPath, 0775, true)) {
        die("Unable create /temp directory, check directory permission and try again!");
    }
}

/* check writable */
if (!is_writable($venPath)) die("Unable to write to /vendor directory, check directory permission and try again!");
if (!is_writable($neuPath)) die("Unable to write to /vendor/Neuron directory, check directory permission and try again!");

/* write test */
@chmod($venPath, 0775);
$fp = @fopen ($zipFile, 'w+') or die("Unable to write a file! Check permission for directory /temp and make sure it's writable by your web server.");

/* download ZF */
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_USERAGENT, 'any');
// curl_setopt($ch, CURLOPT_SSLVERSION, 5); // TLS 1.1
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_exec($ch);
curl_close($ch);
fclose($fp);

/* open zip */
try {
    $zip = new \ZipArchive;
} catch (\Exception $e) {
    die("ZipArchive extension required!");
}
if ($zip->open($zipFile) === true) {

    /* extract */
    $zip->extractTo($venPath);
    $zip->close();

    /* rename src to Zend */
    try {
        rename("{$venPath}/zend-master/src", "{$venPath}/Zend");
    } catch (\Exception $e) {
        die('Failed when renaming Zend directory!');
    }
    @chmod("{$venPath}/Zend", 0775);

    /* del zip file and zend-master */
    unlink($zipFile);
    @chmod("{$venPath}/zend-master", 0775);   //NOTE: Windows gag bisa di delete???
    @unlink("{$venPath}/zend-master");

    /* success */
    echo("OK");
    //header("Refresh:0");

} else {

    die("Unable to extract file! Check permission for directory /vendor and make sure it's writable by your web server.");

}