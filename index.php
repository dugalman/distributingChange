<?php

/**
 * @use php index.php
 */

require_once "./src/Math.php";


$math = new Math();



echo "El factorial 5 debe ser 120 |" . $math->factorial(5);
echo PHP_EOL;


echo "El fibonachi 10 debe ser 55 |" . $math->fibonacci(10);
