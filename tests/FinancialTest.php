<?php


/**
 * @see https://riptutorial.com/phpunit
 */


// use PHPUNIT_Framework_TestCase as TestCase;
// sometimes it can be

use PHPUnit\Framework\TestCase as TestCase;;

use App\Financial;

class FinancialTest extends TestCase
{

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambioRestringidoMaximo50pesos
     */
    public function testDevolverCambioRestringidoA50Pesos($valor, $expect)
    {
        $financial = new Financial();

        $limite = 50;
        $monedas = [1000, 500, 200, 100, 50];
        $rta = $financial->devolverCambio($valor, $monedas, $limite);

        $this->assertEquals(
            number_format($expect['resto'] / 100, 2),
            number_format($rta['resto'] / 100, 2)
        );
    }

    public function providerDevolverCambioRestringidoMaximo50pesos()
    {
        return array(
            array(1500.01, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 0.01],),
            array(1549.99, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 49.99],),
            array(1550, ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 0],),
            array(1550.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 0.01],),
            array(1570.10, ['resultados' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1], 'resto' => 20.10],),
        );
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambioRestringidoMaximo100pesos
     */
    public function testDevolverCambioRestringidoA100Pesos($valor, $expect)
    {
        $financial = new Financial();

        $limite = 100;
        $monedas = [1000, 500, 200, 100];
        $rta = $financial->devolverCambio($valor, $monedas, $limite);

        // verifico como se distribuyen los billetes
        $this->assertEquals($expect['resultados'], $rta['resultados']);

        //verifico que el resto sea menor que el limite
        $this->assertLessThan($limite, $rta['resto']);

        // verifico el resto que va a ir al ticket
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

    ////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Test with data from dataProvider
     * 
     * @dataProvider providerDevolverCambioSinBilletesde100pesos()
     */
    public function testDevolverCambioSinBilletesde100Pesos($valor, $expect)
    {
        $financial = new Financial();

        $limite = 100 * 2;
        // $monedas = [1000, 500, 200, 100];
        $monedas = [1000, 500, 200];
        $rta = $financial->devolverCambio($valor, $monedas);

        // verifico como se distribuyen los billetes
        $this->assertEquals($expect['resultados'], $rta['resultados']);

        //verifico que el resto sea menor que el limite
        $this->assertLessThan($limite, $rta['resto']);

        // Verifico el resto que va a ir al ticket
        // $this->assertSame($expect['resto'], $rta['resto'], "xxxx");

        $this->assertEquals(
            number_format($expect['resto'], 2),
            number_format($rta['resto'], 2),
            "Verifico el resto que va a ir al ticket"
        );
    }

    public function providerDevolverCambioSinBilletesde100pesos()
    {
        return array(
            array(1000.01, ['resultados' => ['1000.00' => 1], 'resto' => 0.01],),
            array(1099.99, ['resultados' => ['1000.00' => 1], 'resto' => 99.99],),
            array(1100.00, ['resultados' => ['1000.00' => 1,], 'resto' => 100],),
            array(1100.01, ['resultados' => ['1000.00' => 1,], 'resto' => 100.01],),
            array(1199.99, ['resultados' => ['1000.00' => 1,], 'resto' => 199.99],),
            // //
            array(1200.01, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 0.01],),
            array(1299.99, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 99.99],),
            array(1300.00, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 100],),
            array(1300.01, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 100.01],),
            array(1399.99, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 199.99],),
            // //
            array(1300.01, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 100.01],),
            array(1399.99, ['resultados' => ['1000.00' => 1, '200.00' => 1], 'resto' => 199.99],),
            array(1400.00, ['resultados' => ['1000.00' => 1, '200.00' => 2], 'resto' => 0],),
            array(1400.01, ['resultados' => ['1000.00' => 1, '200.00' => 2,], 'resto' => 0.01],),
            array(1499.99, ['resultados' => ['1000.00' => 1, '200.00' => 2,], 'resto' => 99.99],),
            #    // //
            array(1500.01, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 0.01],),
            array(1599.99, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 99.99],),
            array(1600.00, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 100],),
            array(1600.01, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 100.01],),
            array(1699.99, ['resultados' => ['1000.00' => 1, '500.00' => 1], 'resto' => 199.99],),
            #    // //
            array(1700.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 0.01],),
            array(1799.99, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 99.99],),
            array(1800.00, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 100],),
            array(1800.01, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 100.01],),
            array(1899.99, ['resultados' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1], 'resto' => 199.99],),
            #    #
            array(2000.01, ['resultados' => ['1000.00' => 2], 'resto' => 0.01],),
            array('2099.99', ['resultados' => ['1000.00' => 2], 'resto' => 99.99],),
            array(2100.00, ['resultados' => ['1000.00' => 2], 'resto' => 100],),
            array(2100.01, ['resultados' => ['1000.00' => 2,], 'resto' => 100.01],),
            array(2199.99, ['resultados' => ['1000.00' => 2,], 'resto' => 199.99],),
        );
    }
}
