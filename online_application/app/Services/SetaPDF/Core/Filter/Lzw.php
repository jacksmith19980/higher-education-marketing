<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Lzw.php 1765 2022-07-28 07:09:20Z maximilian.kresse $
 */

/**
 * Class for handling LZW compression
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Filter_Lzw extends SetaPDF_Core_Filter_Predictor
{
    /**
     * @var string
     */
    protected $_data;

    /**
     * @var array
     */
    protected $_sTable = [];

    /**
     * @var int
     */
    protected $_dataLength = 0;

    /**
     * @var
     */
    protected $_tIdx;

    /**
     * @var int
     */
    protected $_bitsToGet = 9;

    /**
     * @var
     */
    protected $_bytePointer;

    /**
     * @var int
     */
    protected $_nextData = 0;

    /**
     * @var int
     */
    protected $_nextBits = 0;

    /**
     * @var array
     */
    protected $_andTable = [511, 1023, 2047, 4095];

    /**
     * Method to decode LZW compressed data.
     *
     * @param string $data The compressed data
     * @return string The uncompressed data
     * @throws SetaPDF_Core_Filter_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    public function decode($data)
    {
        if ($data[0] === "\x00" && $data[1] === "\x01") {
            throw new SetaPDF_Core_Filter_Exception(
                'LZW flavour not supported.',
                SetaPDF_Core_Filter_Exception::LZW_FLAVOUR_NOT_SUPPORTED
            );
        }

        $this->_initsTable();

        $this->_data = $data;
        $this->_dataLength = strlen($data);

        // Initialize pointers
        $this->_bytePointer = 0;

        $this->_nextData = 0;
        $this->_nextBits = 0;

        $prevCode = 0;

        $uncompData = '';

        while (($code = $this->_getNextCode()) !== 257) {
            if ($code === 256) {
                $this->_initsTable();
            } elseif ($prevCode === 256) {
                $uncompData .= $this->_sTable[$code];
            } elseif ($code < $this->_tIdx) {
                $string = $this->_sTable[$code];
                $uncompData .= $string;

                $this->_addStringToTable($this->_sTable[$prevCode], $string[0]);
            } else {
                $string = $this->_sTable[$prevCode];
                $string .= $string[0];
                $uncompData .= $string;

                $this->_addStringToTable($string);
            }
            $prevCode = $code;
        }

        return parent::decode($uncompData);
    }

    /**
     * Initialize the string table.
     */
    protected function _initsTable()
    {
        $this->_sTable = [];

        for ($i = 0; $i < 256; $i++) {
            $this->_sTable[$i] = chr($i);
        }

        $this->_tIdx = 258;
        $this->_bitsToGet = 9;
    }

    /**
     * Add a new string to the string table.
     *
     * @param string $oldString
     * @param string $newString
     */
    protected function _addStringToTable($oldString, $newString = '')
    {
        $string = $oldString . $newString;

        // Add this new String to the table
        $this->_sTable[$this->_tIdx++] = $string;

        if ($this->_tIdx === 511) {
            $this->_bitsToGet = 10;
        } elseif ($this->_tIdx === 1023) {
            $this->_bitsToGet = 11;
        } elseif ($this->_tIdx === 2047) {
            $this->_bitsToGet = 12;
        }
    }

    /**
     * Returns the next 9, 10, 11 or 12 bits.
     *
     * @return int
     */
    protected function _getNextCode()
    {
        if ($this->_bytePointer === $this->_dataLength) {
            return 257;
        }

        $this->_nextData = ($this->_nextData << 8) | (ord($this->_data[$this->_bytePointer++]) & 0xff);
        $this->_nextBits += 8;

        if ($this->_nextBits < $this->_bitsToGet) {
            $this->_nextData = ($this->_nextData << 8) | (ord($this->_data[$this->_bytePointer++]) & 0xff);
            $this->_nextBits += 8;
        }

        $code = ($this->_nextData >> ($this->_nextBits - $this->_bitsToGet)) & $this->_andTable[$this->_bitsToGet - 9];
        $this->_nextBits -= $this->_bitsToGet;

        return $code;
    }

    /**
     * Encodes a string using LZW algorithm.
     *
     * @see SetaPDF_Core_Filter_Predictor::encode()
     * @param string $data
     * @return string
     * @throws SetaPDF_Exception_NotImplemented
     * @todo Implement
     * @internal
     */
    public function encode($data)
    {
        // TODO: implement LZW encoding
        throw new SetaPDF_Exception_NotImplemented(
            'LZW encoding not implemented.'
        );
    }
}
