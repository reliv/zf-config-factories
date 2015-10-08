<?php


namespace Reliv\FactoriesAsConfiguration\Helper;

/**
 * Class Instantiator This class helps instantiate classes
 * that have an unknown number of constructor arguments.
 *
 * @package Reliv\FactoriesAsConfiguration
 */
class Instantiator
{
    /**
     * Instantiate a class with the given arguments and return it.
     *
     * This function has 30 case statements to increase performance.
     * Instantiation using reflection takes 70% more time than standard
     * instantiation. We fall back to reflection only if a class has more
     * than 30 constructor arguments. Classes with more than 30 constructor
     * arguments should be extremely rare.
     *
     * @param string $className the class name to instantiate
     * @param array $arguments the arguments to pass in
     *
     * @return Object
     */
    public function instantiateWithArguments($className, Array $arguments)
    {
        $a = $arguments;
        switch (count($a)) {
            case 1:
                return new $className($a[0]);
                break;
            case 2:
                return new $className($a[0], $a[1]);
                break;
            case 3:
                return new $className($a[0], $a[1], $a[2]);
                break;
            case 4:
                return new $className($a[0], $a[1], $a[2], $a[3]);
                break;
            case 5:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4]);
                break;
            case 6:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
                break;
            case 7:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]);
                break;
            case 8:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
                break;
            case 9:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8]);
                break;
            case 10:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9]);
                break;
            case 11:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10]);
                break;
            case 12:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11]);
                break;
            case 13:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12]);
                break;
            case 14:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13]);
                break;
            case 15:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14]);
                break;
            case 16:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15]);
                break;
            case 17:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16]);
                break;
            case 18:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17]);
                break;
            case 19:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18]);
                break;
            case 20:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19]);
                break;
            case 21:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20]);
                break;
            case 22:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21]);
                break;
            case 23:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22]);
                break;
            case 24:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23]);
                break;
            case 25:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24]);
                break;
            case 26:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24], $a[25]);
                break;
            case 27:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24], $a[25], $a[26]);
                break;
            case 28:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24], $a[25], $a[26], $a[27]);
                break;
            case 29:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24], $a[25], $a[26], $a[27], $a[28]);
                break;
            case 30:
                return new $className($a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10],
                    $a[11], $a[12], $a[13], $a[14], $a[15], $a[16], $a[17], $a[18], $a[19], $a[20], $a[21], $a[22],
                    $a[23], $a[24], $a[25], $a[26], $a[27], $a[28], $a[29]);
                break;
            default:
                $serviceClass = new \ReflectionClass($className);

                return $serviceClass->newInstanceArgs($a);
        }
    }
}
