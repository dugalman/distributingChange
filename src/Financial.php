<?php

class Financial
{


    /**
     * Distribuye un valor entre distintas denominaciones
     * 
     * @param float      $valor_original   : Que se desea revisar
     * @param array(int) $monedas_original : lista con las monedas
     * 
     * @return array('resultados','resto')
     */
    public function devolverCambio($valor_original, array $monedas_original )
    {
        $valor  = intval($valor_original * 100);

        $monedas = array();
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
            // echo PHP_EOL."1----".$valor."-----".$cantidad."-----".$moneda;
            $valor -= $cantidad * $moneda;
            // echo PHP_EOL."2----".$valor."-----".$cantidad."-----".$moneda;
        }

        // El resto deberia ser la variable $valor, PERO muchas ves hay error de redondeo
        // Para evitar eso, calculo la diferencia entre el valor original y el calculado
        $efectivo = 0;
        foreach ($resultados as $moneda => $cantidad) {
            $efectivo += $moneda * $cantidad;
        }
        // echo PHP_EOL."3----".($valor_original - $resto)."  === ".$valor;
        

        // Devuelve el array de resultados
        return array(
            'resultados' => array_map('intval', $resultados),
            'resto' => $valor_original - $efectivo,
            'efectivo' => $efectivo

        );
    }
}
