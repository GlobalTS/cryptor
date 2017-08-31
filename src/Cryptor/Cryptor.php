<?php
/**
 * Created by PhpStorm.
 * User: topot
 * Date: 31.08.2017
 * Time: 10:55
 */

namespace GlobalTS\Cryptor;

use GlobalTS\Cryptor\Contracts\CryptHandlerInterface;
use GlobalTS\Cryptor\Contracts\CryptorInterface;


/**
 * Class Cryptor
 * @package GlobalTS\Cryptor
 */
class Cryptor implements CryptorInterface
{
    /**
     * @var string
     */
    private $key;
    
    /**
     * @var CryptHandlerInterface
     */
    private $handler;
    
    /**
     * Cryptor constructor.
     * @param CryptHandlerInterface $handler
     * @param string $key
     */
    public function __construct(CryptHandlerInterface $handler, $key = 'default-key')
    {
        $this->handler = $handler;
        $this->key     = $key;
    }
    
    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * @param string $key
     * @return Cryptor
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * @param string $string
     * @return string
     */
    public function encode($string)
    {
        $key             = $this->handler->makeKey($this->key);
        $newBlock        = str_replace('^', '~', $string);
        $len             = strlen($newBlock);
        $blockPart       = '';
        $fullCipherBlock = '';
        for ($i = 0; $i < $len; $i++) {
            $blockPart .= $newBlock[$i];
            if (strlen($blockPart) == 16) {
                $fullCipherBlock .= $this->handler->blockEncrypt($blockPart, $key);
                $blockPart       = '';
            }
        }
        if (strlen($blockPart) < 16) {
            $lenLeft = 16 - strlen($blockPart);
            $addStr  = '';
            for ($i = 0; $i < $lenLeft; $i++) {
                $addStr .= '^';
            }
            $blockPart       .= $addStr;
            $fullCipherBlock .= $this->handler->blockEncrypt($blockPart, $key);
        }
        return $this->handler->toHexString($fullCipherBlock);
    }
    
    /**
     * @param string $string
     * @return string
     */
    public function decode($string)
    {
        $key             = $this->handler->makeKey($this->key);
        $newBlock        = $this->handler->fromHexString($string);
        $len             = strlen($newBlock);
        $blockPart       = '';
        $fullCipherBlock = '';
        for ($i = 0; $i < $len; $i++) {
            $blockPart .= $newBlock[$i];
            if (strlen($blockPart) == 16) {
                $fullCipherBlock .= $this->handler->blockDecrypt($blockPart, $key);
                $blockPart       = '';
            }
        }
        $fullCipherBlock = str_replace('^', '', $fullCipherBlock);
        
        return $fullCipherBlock;
    }
    
}
