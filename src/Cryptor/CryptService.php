<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 14.10.2014
 * Time: 0:55
 */

namespace GlobalTS\Cryptor;

use GlobalTS\Cryptor\Contracts\CryptHandlerInterface;
use GlobalTS\Cryptor\Contracts\CryptInterface;


/**
 * Class CryptService
 * @package GlobalTS\Cryptor
 */
class CryptService implements CryptInterface
{
    /**
     * @var string
     */
    private $key;
    
    /**
     * @var int
     */
    private $shortlinkLength;
    
    /**
     * @var AES128
     */
    private $handler;
    
    /**
     * CryptService constructor.
     * @param CryptHandlerInterface $handler
     * @param string $key
     * @param int $shortlinkLength
     */
    public function __construct(CryptHandlerInterface $handler, $key, $shortlinkLength = 32)
    {
        $this->handler         = $handler;
        $this->key             = $key;
        $this->shortlinkLength = $shortlinkLength;
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
    
    /**
     * @return string
     */
    public function generateRandomString()
    {
        $arr = [
            'a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            '1', '2', '3', '4', '5', '6',
            '7', '8', '9', '0'];
        
        $result = "";
        for ($i = 0; $i < $this->shortlinkLength; $i++) {
            // Вычисляем случайный индекс массива
            $index  = rand(0, count($arr) - 1);
            $result .= $arr[$index];
        }
        return $result;
    }
    
}
