<?php

include 'site.class.php';

if (php_sapi_name() !== 'cli') {
    header('Location: index.html');
    die();
}

if (file_exists('config.ini')) {
    $configs = parse_ini_file('config.ini');
} elseif (file_exists('config.ini.dist')) {
    $configs = parse_ini_file('config.ini.dist');
} else {
    die('ERROR - Archivo de configuración no encontrado'.PHP_EOL);
}

if (file_exists('.sites')) {
    $sitios = explode("\n", trim(file_get_contents('.sites')));
} else {
    die('ERROR - No hay sitios para comprobar, crear archivo .sites'.PHP_EOL);
}


$contenido = '
<!--  Ꙩ_Ꙩ  -->
<html>
<head>
<title>Estado Sitios Web</title>
<link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="./styles.css">

</head>
<body>
<h1>Estado sitios Web</h1>
<table>
<tr>
<th>Sitio</th>
<th>HTTP</th>
<th>HTTPS</th>
</tr>
';

foreach ($sitios as $sitio) {
    $Site = new Site($sitio);
    echo 'Comprobando '.$Site->sitename().PHP_EOL;
    $contenido .= '<tr>';
    $contenido .= $Site->header();
    $contenido .= $Site->result(false);
    $contenido .= $Site->result(true);
    $contenido .= '</tr>'."\n";
}

$contenido .= '
</table>
<div class="footer">&Uacute;ltimo chequeo: '.date('d-m-Y H:i:s').'</div>
</body>
</html>';

file_put_contents('index.html', $contenido);
