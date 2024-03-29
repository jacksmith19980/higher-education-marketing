<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Ascii85.php 1753 2022-06-28 14:42:54Z maximilian.kresse $
 */

/**
 * Class for handling ASCII base-85 data
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Filter
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Filter_Ascii85 implements SetaPDF_Core_Filter_FilterInterface
{
    /**
     * Decode ASCII85 encoded string.
     *
     * @see SetaPDF_Core_Filter_FilterInterface::decode()
     * @param string $data The input string
     * @return string
     * @throws SetaPDF_Core_Filter_Exception
     */
    public function decode($data)
    {
        $out = '';
        $state = 0;
        $chn = null;

        $data = preg_replace('/\s/', '', $data);

        $l = strlen($data);

        for ($k = 0; $k < $l; ++$k) {
            $ch = ord($data[$k]) & 0xff;

            //Start <~
            if($k === 0 && $ch === 60 && isset($data[$k + 1]) && (ord($data[$k + 1]) & 0xFF) === 126) {
                $k++;
                continue;
            }
            //End ~>
            if ($ch === 126 && isset($data[$k + 1]) && (ord($data[$k + 1]) & 0xFF) === 62) {
                break;
            }

            if ($ch === 122 /* z */ && $state === 0) {
                $out .= chr(0) . chr(0) . chr(0) . chr(0);
                continue;
            }
            if ($ch < 33 /* ! */ || $ch > 117 /* u */) {
                throw new SetaPDF_Core_Filter_Exception(
                    'Illegal character found while ASCII85 decode.',
                    SetaPDF_Core_Filter_Exception::ILLEGAL_CHAR_FOUND
                );
            }

            $chn[$state++] = $ch - 33;/* ! */

            if ($state === 5) {
                $state = 0;
                $r = 0;
                for ($j = 0; $j < 5; ++$j) {
                    $r = $r * 85 + $chn[$j];
                }

                $out .= chr($r >> 24)
                    . chr($r >> 16)
                    . chr($r >> 8)
                    . chr($r);
            }
        }

        if ($state === 1) {
            throw new SetaPDF_Core_Filter_Exception(
                'Illegal length while ASCII85 decode.',
                SetaPDF_Core_Filter_Exception::ILLEGAL_LENGTH
            );
        }

        if ($state === 2) {
            $r = $chn[0] * 85 * 85 * 85 * 85 + ($chn[1] + 1) * 85 * 85 * 85;
            $out .= chr($r >> 24);

        } elseif ($state === 3) {
            $r = $chn[0] * 85 * 85 * 85 * 85 + $chn[1] * 85 * 85 * 85 + ($chn[2] + 1) * 85 * 85;
            $out .= chr($r >> 24);
            $out .= chr($r >> 16);

        } elseif ($state === 4) {
            $r = $chn[0] * 85 * 85 * 85 * 85 + $chn[1] * 85 * 85 * 85 + $chn[2] * 85 * 85 + ($chn[3] + 1) * 85;
            $out .= chr($r >> 24);
            $out .= chr($r >> 16);
            $out .= chr($r >> 8);
        }

        return $out;
    }

    /**
     * Encode a string to ASCII85.
     *
     * @see SetaPDF_Core_Filter_FilterInterface::encode()
     * @param string $data
     * @return string
     * @throws SetaPDF_Exception_NotImplemented
     * @todo Implement
     * @internal
     */
    public function encode($data)
    {
        // TODO: implement ASCII85 encoding
        throw new SetaPDF_Exception_NotImplemented(
            'ASCII85 encoding not implemented.'
        );
    }
}
