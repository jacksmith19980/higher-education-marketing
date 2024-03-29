<?php 
/**
 * This file is part of the SetaPDF-Core Component
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: IndirectObjectInterface.php 1706 2022-03-28 10:40:28Z jan.slabon $
 */

/**
 * Interface indirect objects and object references
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Type
 * @license    https://www.setasign.com/ Commercial
 */
interface SetaPDF_Core_Type_IndirectObjectInterface
{
    /**
     * Returns the initial object id.
     *
     * @return integer
     */
    public function getObjectId();
    
    /**
     * Returns the initial generation number.
     *
     * @return integer
     */
    public function getGen();
    
    /**
     * Get the Object Identifier.
     *
     * This identifier has nothing to do with the object numbers
     * of a PDF document. They will be used to map an object to
     * document related object numbers.
     *
     * @return string
     */
    public function getObjectIdent();
    
    /**
     * Returns the owner document.
     *
     * @return SetaPDF_Core_Document
     */
    public function getOwnerPdfDocument();

    /**
     * Ensures the access to the value.
     *
     * @param boolean $forceObservation
     * @return SetaPDF_Core_Type_AbstractType
     */
    public function ensure($forceObservation = null);
}