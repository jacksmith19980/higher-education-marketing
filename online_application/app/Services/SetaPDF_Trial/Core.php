<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Core.php 1855 2023-04-28 10:34:15Z jan.slabon $
 */

/**
 * The class for main properties of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core
{
    /**
     * The version
     *
     * @var string
     */
    const VERSION = '2.42.0.1871';

    /**
     * A float comparison precision
     *
     * @var float
     */
    const FLOAT_COMPARISON_PRECISION = 1e-5;

    /**
     * @param int|float $number
     * @return bool
     */
    public static function isZero($number)
    {
        return abs($number) < self::FLOAT_COMPARISON_PRECISION;
    }

    /**
     * @param int|float $number
     * @return bool
     */
    public static function isNotZero($number)
    {
        return !self::isZero($number);
    }
}
