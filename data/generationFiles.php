<?php

$pattern = '/dist.[a-zA-Z]+_?[a-zA-Z]+?.json$/';
$files = scandir(__DIR__);

foreach ($files as $file) {
    preg_match($pattern, $file, $distFiles);
    if (!empty($distFiles)) {
        $fileName = substr($distFiles[0], 5);
        file_put_contents($fileName, file_get_contents($distFiles[0]));
    }
}
