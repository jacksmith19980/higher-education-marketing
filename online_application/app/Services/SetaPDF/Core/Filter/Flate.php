<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Flate.php 1836 2023-03-07 10:03:04Z jan.slabon $
 */

/**
 * Class for handling zlib/deflate compression
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Filter_Flate extends SetaPDF_Core_Filter_Predictor
{
    /**
     * Checks whether the zlib extension is loaded.
     *
     * Used for testing purpose.
     *
     * @return boolean
     * @internal
     */
    protected function _extensionLoaded()
    {
        return extension_loaded('zlib');
    }

    /**
     * Decodes a flate compressed string.
     *
     * @param string $data The input string
     * @return string
     * @throws SetaPDF_Core_Filter_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    public function decode($data)
    {
        // TODO: better error handling (error_get_last())
        if ($this->_extensionLoaded()) {
            $oData = $data;
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $data = @(($data !== '') ? gzuncompress($data) : '');
            if ($data === false) {
                // let's try if the checksum is CRC32
                $fh = fopen('php://temp', 'w+b');
                fwrite($fh, "\x1f\x8b\x08\x00\x00\x00\x00\x00" . $oData);
                // "window" == 31 -> 16 + (8 to 15): Uses the low 4 bits of the value as the window size logarithm.
                //                   The input must include a gzip header and trailer (via 16).
                stream_filter_append($fh, 'zlib.inflate', STREAM_FILTER_READ, ['window' => 31]);
                fseek($fh, 0);
                /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                $data = @stream_get_contents($fh);
                fclose($fh);

                /* PHP >= 7: Info: This methods seem to handle both checksum formats (Adler32 and CRC32)
                $infl = inflate_init(ZLIB_ENCODING_DEFLATE);
                $data = inflate_add($infl, $oData);
                unset($infl);
                */

                if ($data) {
                    return parent::decode($data);
                }

                // Try this fallback
                $tries = 0;

                $oDataLen = strlen($oData);
                while ($tries < 6 && ($data === false || (strlen($data) < ($oDataLen - $tries - 1)))) {
                    /** @noinspection CallableParameterUseCaseInTypeContextInspection */
                    $data = @(gzinflate(substr($oData, $tries)));
                    $tries++;
                }

                // let's use this fallback only if the $data is longer than the original data
                if (strlen($data) > ($oDataLen - $tries - 1)) {
                    return $data;
                }

                throw new SetaPDF_Core_Filter_Exception(
                    'Error while decompressing stream.',
                    SetaPDF_Core_Filter_Exception::DECOMPRESS_ERROR
                );

            }
        } else {
            throw new SetaPDF_Core_Filter_Exception(
                'To handle FlateDecode filter, enable zlib support in PHP.',
                SetaPDF_Core_Filter_Exception::NO_ZLIB
            );
        }

        return parent::decode($data);
    }

    /**
     * Encodes a string with flate compression.
     *
     * @param string $data The input string
     * @return string
     * @throws SetaPDF_Core_Filter_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    public function encode($data)
    {
        $data = parent::encode($data);

        if ($this->_extensionLoaded()) {
            $data = gzcompress($data);
        } else {
            throw new SetaPDF_Core_Filter_Exception(
                'To handle FlateDecode filter, enable zlib support in PHP.',
                SetaPDF_Core_Filter_Exception::NO_ZLIB
            );
        }

        return $data;
    }
}
