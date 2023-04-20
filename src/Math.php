<?php

class Math
{

    /**
     * Distribuye un valor entre distintas denominaciones
     * 
     * @param number     $valor_original   : Que se desea revisar
     * @param array(int) $monedas_original : lista con las monedas
     * 
     * @return array
     */
    public function devolverCambio($valor_original = 0, $monedas_original = array())
    {
        $valor = intval($valor_original * 100);
        
        $monedas=array();
        foreach ($monedas_original as $moneda) {
             $monedas[] = intval($moneda * 100);
        }
        
        // Ordena las monedas en orden descendente
        rsort($monedas);

        // Inicializa el array de resultados
        $resultados = array();

        
        // Itera a través de cada moneda y calcula la cantidad necesaria
        foreach ($monedas as $moneda) {

            // Si la moneda es mayor que el valor, no puede ser utilizada
            // echo "\n[$key ($moneda > $valor)]".(($moneda > $valor)?"T":"F");
            if ($moneda > $valor) {
                continue;
            }

            // Calcula la cantidad de monedas necesarias
            $cantidad = (floor($valor / $moneda));
            // echo "\n\t $cantidad = floor($valor / $moneda))";

            // Almacena la cantidad de monedas necesarias
            $index = number_format($moneda / 100, 2, '.', '');
            $resultados[$index] = $cantidad;

            // Resta el valor de las monedas utilizadas
            $valor -= $cantidad * $moneda;
        }

        // // Si todavía hay un valor restante, no se puede devolver el cambio exacto
        // if ($valor > 0) {
        //     return false;
        // }

        // Devuelve el array de resultados
        return array_map('intval', $resultados);
    }

    /**
     * Distribuye un valor entre distintas denominaciones
     * 
     * @param number     $valor_original   : Que se desea revisar
     * @param array(int) $monedas_original : lista con las monedas
     * @param number     $limite_original  : tope para generar un ticket vuelto
     * 
     * @return array
     */
    public function devolverCambioRestringido($valor_original = 0, $monedas_original = array(), $limite_original = 0)
    {
        $valor = intval($valor_original * 100);
        $limite = intval($limite_original * 100);
        
        // quito to esa moneda


        $monedas=array();
        foreach ($monedas_original as $moneda) {
             $monedas[] = intval($moneda * 100);
        }
        
        // Ordena las monedas en orden descendente
        rsort($monedas);

        // Inicializa el array de resultados
        $resultados = array();

        
        // Itera a través de cada moneda y calcula la cantidad necesaria
        foreach ($monedas as $moneda) {

            // Si la moneda es mayor que el valor, no puede ser utilizada
            // echo "\n[$key ($moneda > $valor)]".(($moneda > $valor)?"T":"F");
            if ($moneda > $valor) {
                continue;
            }

            // Calcula la cantidad de monedas necesarias
            $cantidad = (floor($valor / $moneda));
            // echo "\n\t $cantidad = floor($valor / $moneda))";

            // Almacena la cantidad de monedas necesarias
            $index = number_format($moneda / 100, 2, '.', '');
            $resultados[$index] = $cantidad;

            // Resta el valor de las monedas utilizadas
            $valor -= $cantidad * $moneda;
        }

        // // Si todavía hay un valor restante, no se puede devolver el cambio exacto
        // if ($valor > 0) {
        //     return false;
        // }

        // Devuelve el array de resultados
        return array(
                'resultados'=> array_map('intval', $resultados),
                'resto' => number_format($valor / 100, 2)
        );
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
