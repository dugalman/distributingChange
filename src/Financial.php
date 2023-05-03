<?php

namespace App;

use InvalidArgumentException;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

/**
 * @version 2.0
 */
class Financial
{
    /**
     * El cantidad de dinero que se desea distribuir
     * 
     * @var float
     */
    private $_valor;

    /**
     * Listado con las denominaciones de billetes disponibles
     * 
     * @var array
     */
    private $_divisas;

    /**
     * Se utiliza para indicar que denominaciones de billetes se puede utilizar
     * 
     * @var float
     */
    private $_limite;

    private $_vuelto;
    private $_efectivo;
    private $_distribucion;


    /**
     * Construnctor de clase
     */
    public function __construct()
    {
        $this->_valor = 0.0;
        $this->_limite = 0.0;
        $this->_divisas = array();
    }

    public function getVuelto()
    {
        return $this->_vuelto;
    }
    public function getEfectivo()
    {
        return $this->_efectivo;
    }
    public function getDistribucion()
    {
        return $this->_distribucion;
    }

    public function setValor($valor)
    {
        if ($valor <= 0) {
            throw new InvalidArgumentException('The value must be a number greater than zero.');
        }

        $this->_valor = floatval($valor);
        return $this;
    }

    public function getValor()
    {
        return $this->_valor;
    }


    public function setLimite($limite)
    {
        if ($limite <= 0) {
            throw new InvalidArgumentException('The limit must be a number greater than zero.');
        }
        $this->_limite = $limite;
        return $this;
    }

    public function getLimite()
    {

        return $this->_limite;
    }

    public function setDivisas(array $divisas)
    {
        if (is_array($divisas) && count($divisas) === 0) {
            throw new InvalidArgumentException('Currency list cannot be empty');
        }

        $this->_divisas = $divisas;
        return $this;
    }

    public function getDivisas()
    {
        return $this->_divisas;
    }

    private function aplicarLimiteALasDivisas()
    {

        $limit = $this->getLimite();
        $out = array();

        foreach ($this->getDivisas() as $divisa) {
            if ($divisa >= $limit) {
                array_push($out, $divisa);
            }
        }
        return $out;
    }

    public function calcularVuelto()
    {
        $valor = $this->getValor();
        $monedas = $this->aplicarLimiteALasDivisas();

        $rta = $this->devolverCambio(
            $this->getValor(),
            $monedas
        );


        $this->_vuelto = $rta['resto'];
        $this->_efectivo = $rta['efectivo'];
        $this->_distribucion = $rta['resultados'];
    }


    /**
     * Distribuye un valor entre distintas denominaciones
     * 
     * @param float      $valor_original   : Que se desea revisar
     * @param array(int) $monedas_original : lista con las monedas
     * 
     * @return array('resultados','resto')
     */
    public function devolverCambio($valor_original, array $monedas_original)
    {
        $valorTemp  = intval($valor_original * 100);

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
            if ($moneda > $valorTemp) {
                continue;
            }

            // Calcula la cantidad de monedas necesarias
            $cantidad = (floor($valorTemp / $moneda));

            // echo "\n\t $cantidad = floor($valorTemp / $moneda))";

            // Almacena la cantidad de monedas necesarias
            $index = number_format($moneda / 100, 2, '.', '');
            $resultados[$index] = $cantidad;

            // Resta el valor de las monedas utilizadas
            // echo PHP_EOL."1----".$valorTemp."-----".$cantidad."-----".$moneda;
            $valorTemp -= $cantidad * $moneda;
            // echo PHP_EOL."2----".$valorTemp."-----".$cantidad."-----".$moneda;
        }

        // El resto deberia ser la variable $valorTemp, PERO muchas ves hay error de redondeo
        // Para evitar eso, calculo la diferencia entre el valor original y el calculado
        $efectivo = 0;
        foreach ($resultados as $moneda => $cantidad) {
            $efectivo += $moneda * $cantidad;
        }
        // echo PHP_EOL."3----".($valor_original - $resto)."  === ".$valorTemp;


        // Devuelve el array de resultados
        return array(
            'resultados' => array_map('intval', $resultados),
            'resto' => $valor_original - $efectivo,
            'efectivo' => $efectivo

        );
    }
}
