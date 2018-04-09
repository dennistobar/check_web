# Check Web

Pequeño script que comprueba el estado de los distintos portales de un sitio web, usando curl y gethostbyname. Se muestra en un formato más amable de colores en caso de acierto o rechazo.

Basado en la herramienta de [David Abián](https://tools.wmflabs.org/status/) y portado a PHP para hacer algunos ajustes adicionales

## Configuración
- Se debe configurar creando el archivo .sites donde se deben listar los dominios a comprobar, uno por fila
- [opcional] Se debe copiar el contenido de config.ini.dist a config.ini para los parámetros mínimos

## Ejecución
Para ejecutarlo, se debe ejecutar por consola el archivo `process.php`

`php process.php`

Una vez realizado, se procesará todo el contenido y se generará un archivo `index.html` el cual contiene el resumen de la información
