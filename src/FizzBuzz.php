<?php

namespace App;


class FizzBuzz
{
    /**
     * Determina el mensaje a monstrar siguiendo las reglas de FIZZ BUZZ
     * 
     * @param int $number : Numero para determinar si es Fizz o Buzz
     * 
     * @return string
     */
    public function calcula($number)
    {
        if (($number % 5) === 0  && ($number % 3) === 0) {
            return 'FizzBuzz';
        }
        if (($number % 5) === 0) {
            return 'Buzz';
        }
        if (($number % 3) === 0) {
            return 'Fizz';
        }
        return $number;
    }
}
