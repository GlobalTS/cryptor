<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 31.08.2017
 * Time: 13:51
 */

namespace GlobalTS\Cryptor\Contracts;

/**
 * Class CryptHandlerInterface
 * @package GlobalTS\Cryptor
 */
interface CryptHandlerInterface
{
    /**
     * @param string $hash
     * @return array
     */
    public function makeKey($hash);
    
    /**
     * @param string $in
     * @param array $key
     * @return string
     */
    public function blockEncrypt($in, array $key);
    
    /**
     * @param string $in
     * @param array $key
     * @return string
     */
    public function blockDecrypt($in, array $key);
    
    /**
     * @param string $sa
     * @return string
     */
    public function toHexString($sa);
    
    /**
     * @param string $sa
     * @return string
     */
    public function fromHexString($sa);
}
