<?php

/**
 * @see https://riptutorial.com/phpunit
 */

require 'Math.php';

// use PHPUNIT_Framework_TestCase as TestCase;
// sometimes it can be

use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use PHPUnit\Framework\TestCase as TestCase;

class MathTest extends TestCase
{
    public function testFibonacci()
    {
        $math = new Math();
        $this->assertEquals(34, $math->fibonacci(9));
    }

    public function testFactorial()
    {
        $math = new Math();
        $this->assertEquals(120, $math->factorial(5));
    }

    public function testFactorialGreaterThanFibonacci()
    {
        $math = new Math();
        $this->assertTrue($math->factorial(6) > $math->fibonacci(6));
    }

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerFibonacci
     */
    public function testFibonacciWithDataProvider($n, $result)
    {
        $math = new Math();
        $this->assertEquals($result, $math->fibonacci($n));
    }
    public function providerFibonacci()
    {
        return array(
            array(1, 1),
            array(2, 1),
            array(3, 2),
            array(4, 3),
            array(5, 5),
            array(6, 8),
        );
    }

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambio
     */
    public function testDevolverCambioWithDataProvider($valor, $monedas, $result)
    {
        $math = new Math();
        $rta = $math->devolverCambio($valor, $monedas);
        $this->assertEquals($result, $rta);
    }

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambioRestringidoMaximo50pesos
     */
    public function testDevolverCambioRestringidoA50Pesos($valor, $limite, $monedas, $result)
    {
        $math = new Math();
        $rta = $math->devolverCambioRestringido($valor, $monedas, $limite);

        $this->assertEquals($result, $rta);

        //verifico que el resto sea menor que el limite
        $this->assertLessThan($limite, $rta['resto']);
    }

    public function providerDevolverCambioRestringidoMaximo50pesos()
    {
        return array(
            array(
                1500.01,
                50,
                [1000, 500, 200, 100, 50],
                ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 0.01],
            ),
            array(
                1549.99,
                50,
                [1000, 500, 200, 100, 50],
                ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 49.99],
            ),
            array(
                1550,
                50,
                [1000, 500, 200, 100, 50],
                ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 0],
            ),
            array(
                1550.01,
                50,
                [1000, 500, 200, 100, 50],
                ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 0.01],
            ),
            array(
                1570.10,
                50,
                [1000, 500, 200, 100, 50],
                ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 20.10],
            ),
        );
    }


    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambioRestringidoMaximo100pesos
     */
    public function testDevolverCambioRestringidoA100Pesos($valor, $expect)
    {
        $math = new Math();

        $limite = 100;
        $monedas = [1000, 500, 200, 100];
        $rta = $math->devolverCambioRestringido($valor, $monedas, $limite);

        // verifico como se distribuyen los billetes
        $this->assertEquals($expect['resultados'], $rta['resultados']);

        //verifico que el resto sea menor que el limite
        $this->assertLessThan($limite, $rta['resto']);

        // verifico el resto que va a ir al ticket
        // $this->assertSame($expect['resto'], $rta['resto']);
        $this->assertEquals(
            number_format($expect['resto'] / 100, 2),
            number_format($rta['resto'] / 100, 2)
        );
    }

    public function providerDevolverCambioRestringidoMaximo100pesos()
    {
        return array(
            array(1000.01, ['resultados' => ['1000.00' => 1], 'resto' => 0.01],),
            array(1099.99, ['resultados' => ['1000.00' => 1], 'resto' => 99.99],),
            array(1100.00, ['resultados' => ['1000.00' => 1, '100.00' => 1], 'resto' => 0],),
            array(1100.01, ['resultados' => ['1000.00' => 1, '100.00' => 1], 'resto' => 0.01],),
            array(1199.99, ['resultados' => ['1000.00' => 1, '100.00' => 1], 'resto' => 99.99],),
            ///
            array(2000.01, ['resultados' => ['1000.00' => 2], 'resto' => 0.01],),
            array(2099.99, ['resultados' => ['1000.00' => 2], 'resto' => 99.99],),
            array(2100.00, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 0],),
            array(2100.01, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 0.01],),
            array(2199.99, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 99.99],),
            //
            array(3000.01, ['resultados' => ['1000.00' => 3], 'resto' => 0.01],),
            array(3099.99, ['resultados' => ['1000.00' => 3], 'resto' => 99.99],),
            array(3100.00, ['resultados' => ['1000.00' => 3, '100.00' => 1], 'resto' => 0],),
            array(3100.01, ['resultados' => ['1000.00' => 3, '100.00' => 1], 'resto' => 0.01],),
            array(3199.99, ['resultados' => ['1000.00' => 3, '100.00' => 1], 'resto' => 99.99],),
            //
            array(1200.01, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 0.01],),
            array(1299.99, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 99.99],),
            array(1300.00, ['resultados' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 0],),
            array(1300.01, ['resultados' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 0.01],),
            array(1399.99, ['resultados' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 99.99],),
            //
            array(1300.01, ['resultados' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 0.01],),
            array(1399.99, ['resultados' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 99.99],),
            array(1400.00, ['resultados' => ['1000.00' => 1, '200.00' => 2], 'resto' => 0],),
            array(1400.01, ['resultados' => ['1000.00' => 1, '200.00' => 2,], 'resto' => 0.01],),
            array(1499.99, ['resultados' => ['1000.00' => 1, '200.00' => 2,], 'resto' => 99.99],),
            //
            array(1500.01, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 0.01],),
            array(1599.99, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 99.99],),
            array(1600.00, ['resultados' => ['1000.00' => 1, '500.00' => 1, '100.00' => 1], 'resto' => 0],),
            array(1600.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '100.00' => 1], 'resto' => 0.01],),
            array(1699.99, ['resultados' => ['1000.00' => 1, '500.00' => 1, '100.00' => 1], 'resto' => 99.99],),
            //
            array(1700.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 0.01],),
            array(1799.99, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 99.99],),
            array(1800.00, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 0],),
            array(1800.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 0.01],),
            array(1899.99, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1, '100.00' => 1], 'resto' => 99.99],),

            array(2000.01, ['resultados' => ['1000.00' => 2], 'resto' => 0.01],),
            array(2099.99, ['resultados' => ['1000.00' => 2], 'resto' => 99.99],),
            array(2100.00, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 0],),
            array(2100.01, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 0.01],),
            array(2199.99, ['resultados' => ['1000.00' => 2, '100.00' => 1], 'resto' => 99.99],),
        );
    }



    public function providerDevolverCambio()
    {
        return array(
            array(
                '63',
                ['25', '10', '5', '1'],
                ['25.00' => 2, '10.00' => 1,  '1.00' => 3],
            ),
            array(
                7,
                [25, 10, 5, 1],
                ['5.00' => 1, '1.00' => 2]
            ),
            array(
                '0.34',
                ['0.01'],
                ['0.01' => 34]
            ),
            array(
                '0.34',
                ['1', '0.01'],
                ['0.01' => 34]
            ),
            array(
                '7.34',
                ['1', '0.01'],
                ['1.00' => 7, '0.01' => 34]
            ),
        );
    }
}
