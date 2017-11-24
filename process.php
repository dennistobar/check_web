<?php

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

function print_site($data) : string
{
    if ($data === false) {
        return '<div class="ops">Error</div>';
    }
    return '<div class="overlay">'.implode("<br />", $data).'</div>';
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

$string_http = 'curl -I http://%s --connect-timeout %s -A "%s" -X GET 2>/dev/null';
$string_https = 'curl -I https://%s --connect-timeout %s -A "%s" -X GET 2>/dev/null';

foreach ($sitios as $sitio) {
    echo 'Comprobando '.$sitio.PHP_EOL;

    $http = exec(sprintf($string_http, $sitio, (int)$configs['timeout'], $configs['user_agent']), $data_http);
    $https = exec(sprintf($string_https, $sitio, (int)$configs['timeout'], $configs['user_agent']), $data_https);
    $contenido .= '<tr>
    <th class="site_name"><span>'.$sitio.'</span><br /><span class="ip">'.gethostbyname($sitio).'</span></th>';
    $contenido .= '<td class="'.(!!$data_http ? 'ok' : 'no').'">'.print_site($data_http).'</td>';
    $contenido .= '<td class="'.(!!$data_https ? 'ok' : 'no').'">'.print_site($data_https).'</td>';
    $contenido .= '</tr>'."\n";
    unset($data_http, $data_https);
}

$contenido .= '
</table>
<div class="footer">&Uacute;ltimo chequeo: '.date('d-m-Y H:i:s').'</div>
</body>
</html>';

file_put_contents('index.html', $contenido);
