<?php


/**
 * @see https://riptutorial.com/phpunit
 */


// use PHPUNIT_Framework_TestCase as TestCase;
// sometimes it can be

use PHPUnit\Framework\TestCase as TestCase;;

use App\Financial;

/**
 * Class TicketVueltoTest
 * 
 * @author "Damian Mac Dougall<damianmacdougall@gmail.com>"
 * 
 */
class TicketVueltoTest extends TestCase
{
    /**
     * Internal variable for test
     * 
     * @var App\Financial
     */
    public $tkv = null;

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Set Up test
     * 
     * @return void
     */
    public function setUp()
    {
        $this->tkv = new Financial();
    }


    public function testSetearYRecuperarLasDivisas()
    {
        $divisas = [1000, 500, 200, 100, 50, 20, 10, 5, 2];

        $this->tkv->setDivisas($divisas);
        $this->assertInternalType('array', $this->tkv->getDivisas());
        $this->assertEquals($divisas, $this->tkv->getDivisas());
    }

    public function testSetearYRecuperarElLimiteConEnteros()
    {
        $limite = 50;

        $this->tkv->setLimite($limite);
        $this->assertInternalType('int', $this->tkv->getLimite());
        $this->assertEquals($limite, $this->tkv->getLimite());
    }

    public function testSetearYRecuperarElValorUsandoUnEntero()
    {
        $valor = 50;

        $this->tkv->setValor($valor);
        $this->assertInternalType('float', $this->tkv->getValor());
        $this->assertEquals($valor, $this->tkv->getValor());
    }

    public function testSetearYRecuperarElValorUsandoUnFloat()
    {
        $valor = 3.14;

        $this->tkv->setValor($valor);
        $this->assertInternalType('float', $this->tkv->getValor());
        $this->assertEquals($valor, $this->tkv->getValor());
    }


    public function testAlSetearDebeDevolverLamismaInstancia()
    {

        $this->assertInstanceOf(Financial::class, $this->tkv);

        $divisas = [1000, 500, 200, 100, 50, 20, 10, 5, 2];
        $valor = 3546.23;
        $limite = 50;

        $rta = $this->tkv
            ->setDivisas($divisas)
            ->setValor($valor)
            ->setLimite($limite);

        $this->assertEquals($divisas, $rta->getDivisas());
        $this->assertEquals($valor, $rta->getValor());
        $this->assertEquals($limite, $rta->getLimite());

        $this->assertInstanceOf(Financial::class, $rta);
    }

    public function testLanzaExcepcionSiElValorEsCero()
    {

        $this->expectException(InvalidArgumentException::class);
        $this->tkv->setValor(0);
    }

    public function testLanzaExcepcionSiElLimiteEsCero()
    {


        $this->expectException(InvalidArgumentException::class);
        $this->tkv->setLimite(0);
    }

    public function testLanzaExcepcionSiLaListaDeDivisasEstaVacia()
    {

        $this->expectException(InvalidArgumentException::class);
        $this->tkv->setDivisas(array());
    }

    public function testDivisasDebeDevolverUnArrayFiltradoDeValoresDeDivisasQueSeaIgualOrMenorQueElLimite()
    {
        $this->tkv
            ->setDivisas([1000, 500, 200, 100, 50, 20, 10, 5])
            ->setLimite(50);

        // $rta = $this->tkv->aplicarLimiteALasDivisas();
        $rta = $this->invokeMethod(
            $this->tkv,
            'aplicarLimiteALasDivisas'
        );
        $this->assertEquals([1000, 500, 200, 100, 50], $rta);
    }


    public function testDivisasDebeDevolverUnArrayVacioSiElLimiteEsMayoyATodosLosValoresDeDivisas()
    {

        $this->tkv
            ->setDivisas([1000, 500, 200, 100])
            ->setLimite(9999);

        // $rta = $this->tkv->aplicarLimiteALasDivisas();
        $rta = $this->invokeMethod(
            $this->tkv,
            'aplicarLimiteALasDivisas'
        );
        $this->assertEquals([], $rta);
    }

    public function testDivisasDebeDevolverElmismoArraySiElLimiteEsMenorATodosLosValoresDeDivisas()
    {

        $this->tkv
            ->setDivisas([1000, 500, 200, 100])
            ->setLimite(99);

        // $rta = $this->tkv->aplicarLimiteALasDivisas();
        $rta = $this->invokeMethod(
            $this->tkv,
            'aplicarLimiteALasDivisas'
        );
        $this->assertEquals([1000, 500, 200, 100], $rta);
    }

    #############################
    # calcularVuelto()
    #############################


    /**
     * ************************************************************************
     * @dataProvider providerCalcularVueltoConLimiteDe50Pesos
     * 
     */
    public function testCalcularVueltoConLimiteDe50Pesos($valor, $expect)
    {
        $monedas = [1000, 500, 200, 100, 50, 20, 10, 5];
        $limite = 50;
        $this->tkv
            ->setValor($valor)
            ->setDivisas($monedas)
            ->setLimite($limite)
            ->calcularVuelto();

        $this->assertEquals($expect['vuelto'], $this->tkv->getVuelto(), 'Fallo el vuelto');
        // $this->assertEquals(1500, $this->tkv->getEfectivo());
        // $this->assertEquals($expect['distribucion'], $this->tkv->getDistribucion(), 'fallo la distribución');
    }


    public function providerCalcularVueltoConLimiteDe50Pesos()
    {
        return array(
            array(1500.01, ['vuelto' =>  0.01, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1500.10, ['vuelto' =>  0.10, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1520.10, ['vuelto' => 20.10, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1535.00, ['vuelto' => 35.00, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1545.10, ['vuelto' => 45.10, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1549.99, ['vuelto' => 49.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1550.00, ['vuelto' =>  0.00, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1]]),
            array(1550.01, ['vuelto' =>  0.01, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1]]),
            array(1570.10, ['vuelto' => 20.10, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '50.00' => 1]]),
        );
    }


    /**
     * ************************************************************************
     * @dataProvider providerCalcularVueltoConLimiteDe100PesosYPermitoBilleteDe100
     * 
     * Si permito billetes de 100 pesos, el vuelto es un numero entre $0.0- y $99.99-
     */
    public function testCalcularVueltoConLimiteDe100PesosYPermitoBilleteDe100($valor, $expect)
    {
        $monedas = [1000, 500, 200, 100, 50, 20, 10, 5];
        $limite = 100;
        $this->tkv
            ->setValor($valor)
            ->setDivisas($monedas)
            ->setLimite($limite)
            ->calcularVuelto();

        $this->assertEquals($expect['vuelto'], $this->tkv->getVuelto(), 'Fallo el vuelto');
        $this->assertEquals($expect['distribucion'], $this->tkv->getDistribucion(), 'fallo la distribución');
    }

    public function providerCalcularVueltoConLimiteDe100PesosYPermitoBilleteDe100()
    {
        return array(

            //
            array(5, ['vuelto' =>  5, 'distribucion' => []]),
            array(10, ['vuelto' =>  10, 'distribucion' => []]),
            array(20, ['vuelto' =>  20, 'distribucion' => []]),
            array(50, ['vuelto' =>  50, 'distribucion' => []]),
            array(100, ['vuelto' =>  0, 'distribucion' => ['100.00' => 1]]),
            array(200, ['vuelto' =>  0, 'distribucion' => ['200.00' => 1]]),
            array(500, ['vuelto' =>  0, 'distribucion' => ['500.00' => 1]]),
            array(1000, ['vuelto' =>  0, 'distribucion' => ['1000.00' => 1]]),
            //
            array(99.99, ['vuelto' =>  99.99, 'distribucion' => []]),
            array(199.99, ['vuelto' =>  99.99, 'distribucion' => ['100.00' => 1]]),
            array(299.99, ['vuelto' =>  99.99, 'distribucion' => ['200.00' => 1]]),
            array(399.99, ['vuelto' =>  99.99, 'distribucion' => ['200.00' => 1, '100.00' => 1]]),
            array(499.99, ['vuelto' =>  99.99, 'distribucion' => ['200.00' => 2]]),
            array(599.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1]]),
            array(699.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '100.00' => 1]]),
            array(799.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '200.00' => 1]]),
            array(899.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '200.00' => 1, '100.00' => 1]]),
            array(999.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '200.00' => 2,]]),
            //
            array(1099.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1,]]),
            array(1199.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '100.00' => 1]]),
            array(1299.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '200.00' => 1]]),
            array(1399.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '200.00' => 1, '100.00' => 1]]),
            array(1499.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '200.00' => 2]]),
            array(1599.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1699.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '100.00' => 1]]),
            array(1799.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1]]),
            array(1899.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1, '100.00' => 1]]),
            array(1999.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 2,]]),


        );
    }
    /**
     * ************************************************************************
     * @dataProvider providerCalcularVueltoConLimiteDe100PesosYNoPermitoBilleteDe100
     * 
     * Si NO permito billetes de 100 pesos, el vuelto es un numero entre $0.0- y $199.99-
     */
    public function testCalcularVueltoConLimiteDe100PesosYNoPermitoBilleteDe100($valor, $expect)
    {
        $monedas = [1000, 500, 200, 100, 50, 20, 10, 5];

        // le sumo 1 al limite para que incluya al billete de 100
        $limite = 101;
        $this->tkv
            ->setValor($valor)
            ->setDivisas($monedas)
            ->setLimite($limite)
            ->calcularVuelto();

        $this->assertEquals($expect['vuelto'], $this->tkv->getVuelto(), 'Fallo el vuelto');
        $this->assertEquals($expect['distribucion'], $this->tkv->getDistribucion(), 'fallo la distribución');
    }

    public function providerCalcularVueltoConLimiteDe100PesosYNoPermitoBilleteDe100()
    {
        return array(

            //
            array(5, ['vuelto' =>  5, 'distribucion' => []]),
            array(10, ['vuelto' =>  10, 'distribucion' => []]),
            array(20, ['vuelto' =>  20, 'distribucion' => []]),
            array(50, ['vuelto' =>  50, 'distribucion' => []]),
            array(100, ['vuelto' =>  100, 'distribucion' => []]),
            array(200, ['vuelto' =>  0, 'distribucion' => ['200.00' => 1]]),
            array(500, ['vuelto' =>  0, 'distribucion' => ['500.00' => 1]]),
            array(1000, ['vuelto' =>  0, 'distribucion' => ['1000.00' => 1]]),
            //
            array(99.99, ['vuelto' =>  99.99, 'distribucion' => []]),
            array(199.99, ['vuelto' => 199.99, 'distribucion' => []]),
            array(299.99, ['vuelto' =>  99.99, 'distribucion' => ['200.00' => 1]]),
            array(399.99, ['vuelto' => 199.99, 'distribucion' => ['200.00' => 1]]),
            array(499.99, ['vuelto' =>  99.99, 'distribucion' => ['200.00' => 2]]),
            array(599.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1]]),
            array(699.99, ['vuelto' => 199.99, 'distribucion' => ['500.00' => 1]]),
            array(799.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '200.00' => 1]]),
            array(899.99, ['vuelto' => 199.99, 'distribucion' => ['500.00' => 1, '200.00' => 1]]),
            array(999.99, ['vuelto' =>  99.99, 'distribucion' => ['500.00' => 1, '200.00' => 2,]]),
            //
            array(1099.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1]]),
            array(1199.99, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1]]),
            array(1299.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '200.00' => 1]]),
            array(1399.99, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1, '200.00' => 1]]),
            array(1499.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '200.00' => 2]]),
            array(1599.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1699.99, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),
            array(1799.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1]]),
            array(1899.99, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1]]),
            array(1999.99, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 2,]]),
        );
    }


    /**
     * ************************************************************************
     * @dataProvider providerCalcularVueltoCambiandoElLimite
     * 
     */
    public function testCalcularVueltoCambiandoElLimite($limite, $expect)
    {
        $monedas = [1000, 500, 200, 100, 50, 20, 10, 5];

        $valor = 1899.99;
        // le sumo 1 al limite para que incluya al billete de 100
        $this->tkv
            ->setValor($valor)
            ->setDivisas($monedas)
            ->setLimite($limite)
            ->calcularVuelto();

        $this->assertEquals($expect['vuelto'], $this->tkv->getVuelto(), 'Fallo el vuelto');
        $this->assertEquals($expect['distribucion'], $this->tkv->getDistribucion(), 'fallo la distribución');
    }

    public function providerCalcularVueltoCambiandoElLimite()
    {
     return array(
            //$valor = 1899.99;

            // Si NO permito billetes de 100 pesos, el vuelto es un numero entre $0.0- y $199.99-
            array(100, ['vuelto' =>  99.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1, '100.00' => 1]]),
            array(101, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1]]),
            
            // Si NO permito billetes de 200 pesos, el vuelto es un numero entre $0.0- y $399.99-
            array(200, ['vuelto' => 199.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1, '200.00' => 1]]),
            array(201, ['vuelto' => 399.99, 'distribucion' => ['1000.00' => 1, '500.00' => 1]]),

        );   
    }


}
