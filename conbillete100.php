<?php

/**
 * @use php index.php
 */
require_once "./src/Financial.php";

function ff($v)
{
    return number_format($v, 2, ',', '');
}


$financial = new Financial();

echo "valor; resto; efectivo";

for ($i = 0; $i < 10000; $i++) {

    for ($j = 0; $j < 100; $j++) {

        $valor = $i + ($j / 100);
        $monedas = [1000, 500, 200];
        $rta = $financial->devolverCambio($valor, $monedas);
        // echo  ff($valor) . " ;  " . ff($rta['resto']) . " ; " . ff($rta['efectivo']);

        if ($valor != ($rta['resto'] +  $rta['efectivo'])) {
            echo PHP_EOL;
            echo  ff($valor) . " ;  " . ff($rta['resto']) . " ; " . ff($rta['efectivo']);
        }
    }
}
