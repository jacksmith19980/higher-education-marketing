<?php
/**
 * This file is part of the SetaPDF package
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @package    SetaPDF
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Autoload.php 1841 2023-03-27 15:14:31Z maximilian.kresse $
 */

spl_autoload_register(static function ($class) {
    if (strpos($class, 'SetaPDF_') === 0) {
        $filename = str_replace('_', DIRECTORY_SEPARATOR, substr($class, 8)) . '.php';
        $fullpath = __DIR__ . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($fullpath)) {
            /** @noinspection PhpIncludeInspection */
            require_once $fullpath;
        }
    }
});