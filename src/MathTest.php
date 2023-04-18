<?php

/**
 * @see https://riptutorial.com/phpunit
 */

require 'Math.php';

// use PHPUNIT_Framework_TestCase as TestCase;
// sometimes it can be
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
    public function providerFibonacci() {
        return array(
            array(1, 1),
            array(2, 1),
            array(3, 2),
            array(4, 3),
            array(5, 5),
            array(6, 8),
        );
    }

}
