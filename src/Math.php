<?php

class Math
{

    /**
     * Distribuye un valor entre distintas denominaciones
     * 
     * @param number     $valor   : Que se desea revisar
     * @param array(int) $monedas : lista con las denominacion de monedas que se pueden utilizar
     * 
     * @return array
     */
    public function devolverCambio($valor = 0, $monedas = array())
    {
        
        // Ordena las monedas en orden descendente
        rsort($monedas, SORT_NUMERIC);

        // Inicializa el array de resultados
        $resultados = array();

        // Itera a través de cada moneda y calcula la cantidad necesaria
        $iteracion = 0;
        foreach ($monedas as $moneda) {
            $iteracion++;
            printf("\n %02s %6s %06s", $iteracion, $moneda , $valor);
            // Si la moneda es mayor que el valor, no puede ser utilizada
            if ($moneda > $valor) {
                continue;
            }

            // Calcula la cantidad de monedas necesarias
            $cantidad = floor($valor / $moneda);
            // echo "($valor / $moneda) = $cantidad";

            // Almacena la cantidad de monedas necesarias
            $resultados["$moneda"] = $cantidad;

            // Resta el valor de las monedas utilizadas
            $valor -= $cantidad * $moneda;
        }

        // Si todavía hay un valor restante, no se puede devolver el cambio exacto
        if ($valor > 0) {
            return false;
        }

        // Devuelve el array de resultados
        return $resultados;
    }

    public function fibonacci($n)
    {
        if (is_int($n) && $n > 0) {
            $elements = array();
            $elements[1] = 1;
            $elements[2] = 1;
            for ($i = 3; $i <= $n; $i++) {
                $elements[$i] = $elements[$i - 1] + $elements[$i - 2];
            }
            return $elements[$n];
        } else {
            throw new
                InvalidArgumentException('You should pass integer greater than 0');
        }
    }

    public function factorial($n)
    {
        if (is_int($n) && $n >= 0) {
            $factorial = 1;
            for ($i = 2; $i <= $n; $i++) {
                $factorial *= $i;
            }
            return $factorial;
        } else {
            throw new
                InvalidArgumentException('You should pass non-negative integer');
        }
    }
}
