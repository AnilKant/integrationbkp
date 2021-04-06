<?php
$zip = new ZipArchive;
if ($zip->open('shopify_jet-2Dec2015.zip') === TRUE) {
    $zip->extractTo(getcwd());
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
?> 