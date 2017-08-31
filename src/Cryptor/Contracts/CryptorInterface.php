<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 17.02.2017
 * Time: 20:19
 */

namespace GlobalTS\Cryptor\Contracts;

/**
 * Interface CryptorInterface
 * @package GlobalTS\Cryptor\Contracts
 */
interface CryptorInterface
{
    /**
     * @param string $string
     * @param string $key
     * @return string
     */
    public function encode($string, $key = null);
    
    /**
     * @param string $string
     * @param string $key
     * @return string
     */
    public function decode($string, $key = null);
    
    /**
     * @return string
     */
    public function getKey();
    
    /**
     * @param string $key
     * @return static
     */
    public function setKey($key);
    
}
