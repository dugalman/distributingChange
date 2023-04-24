<?php

namespace App\Test;

use App\FizzBuzz;
use PHPUnit\Framework\TestCase;

class FizzBuzzTest extends TestCase
{

    public function providerFizzBuzz()
    {
        return [
            [3, 'Fizz'],
            [15, 'FizzBuzz'],
            [5, 'Buzz'],
            [1, 1],
        ];
    }

    /**
     * Prueba el compotamiento de la lacase FizzBuzz
     * 
     * @param int $numberToTest : Numero a probar
     * @param int $expectResult : Resultado esperado
     * 
     * @return void
     * 
     * @dataProvider providerFizzBuzz
     */
    public function testFizzBuzz($numberToTest, $expectResult)
    {

        $fizzBuzz = new FizzBuzz();
        $result = $fizzBuzz->calcula($numberToTest);
        $this->assertEquals($expectResult, $result);
    }
}
