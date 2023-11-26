<?php 
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Arcfour40.php 1782 2022-09-28 12:55:13Z jan.slabon $
 */

/**
 * Generator class for RC4 40 bit security handler
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage SecHandler
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_SecHandler_Standard_Arcfour40 extends SetaPDF_Core_SecHandler_Standard
{
    /**
     * Factory method for RC4 40 bit security handler.
     *
     * @param SetaPDF_Core_Document $document
     * @param string $ownerPassword The owner password in encoding defined in $passwordsEncoding
     * @param string $userPassword The user password in encoding defined in $passwordsEncoding
     * @param integer $permissions
     * @param string $passwordsEncoding
     * @return SetaPDF_Core_SecHandler_Standard_Arcfour40
     * @throws SetaPDF_Core_SecHandler_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    public static function factory(
        SetaPDF_Core_Document $document,
        $ownerPassword,
        $userPassword = '',
        $permissions = 0,
        $passwordsEncoding = SetaPDF_Core_Encoding::PDF_DOC
    )
    {
        $ownerPassword = self::ensurePasswordEncoding(2, $ownerPassword, $passwordsEncoding);
        $userPassword = self::ensurePasswordEncoding(2, $userPassword, $passwordsEncoding);

        $encryptionDict = new SetaPDF_Core_Type_Dictionary();
        $encryptionDict->offsetSet('Filter', new SetaPDF_Core_Type_Name('Standard', true));
        
        $encryptionDict->offsetSet('R', new SetaPDF_Core_Type_Numeric(2));
        $encryptionDict->offsetSet('V', new SetaPDF_Core_Type_Numeric(1));
        $encryptionDict->offsetSet('O', $o = new SetaPDF_Core_Type_String());
        $encryptionDict->offsetSet('U', $u = new SetaPDF_Core_Type_String());

        $permissions = self::ensurePermissions($permissions, 2);
        $encryptionDict->offsetSet('P', new SetaPDF_Core_Type_Numeric($permissions));
        
        $instance = new self($document, $encryptionDict);
        
        $oValue = $instance->_computeOValue($userPassword, $ownerPassword);
        $o->setValue($oValue);
        
        $encryptionKey = $instance->_computeEncryptionKey($userPassword);

        $uValue = $instance->_computeUValue($encryptionKey);
        $u->setValue($uValue);

        $instance->_encryptionKey = $encryptionKey;
        $instance->_auth = true;
        $instance->_authMode = SetaPDF_Core_SecHandler::OWNER;

        return $instance;
    }
}
