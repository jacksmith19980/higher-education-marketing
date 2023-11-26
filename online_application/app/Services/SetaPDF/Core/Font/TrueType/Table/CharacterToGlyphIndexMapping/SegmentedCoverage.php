<?php
/**
 * This file is part of the SetaPDF-Core Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: SegmentedCoverage.php 1838 2023-03-10 09:32:38Z jan.slabon $
 */

/**
 * A class representing a subtable "Format 12: Segmented coverage".
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Font
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Font_TrueType_Table_CharacterToGlyphIndexMapping_SegmentedCoverage extends
    SetaPDF_Core_Font_TrueType_Table_CharacterToGlyphIndexMapping_SubTable
{
    /**
     * The entries of this subtable
     *
     * @var array
     */
    protected $_entries = [
        'format' => [0, SetaPDF_Core_BitConverter::USHORT],
        'length' => [4, SetaPDF_Core_BitConverter::ULONG],
        'language' => [8, SetaPDF_Core_BitConverter::ULONG],
        'nGroups' => [12, SetaPDF_Core_BitConverter::ULONG],
    ];

    /**
     * The groups
     *
     * @var array
     */
    protected $_groups = [];

    /**
     * Last groups accessed
     *
     * @var integer
     */
    protected $_lastGroup = 0;

    /**
     * Get the number of groups.
     *
     * @return integer
     */
    public function getNGroups()
    {
        return $this->_get('nGroups');
    }

    /**
     * Get the glyph index by a character code.
     *
     * @param integer $charCode
     * @return integer
     */
    public function getGlyphIndex($charCode)
    {
        foreach ($this->_groups AS $group) {
            if ($charCode >= $group[0] && $charCode <= $group[1]) {
                return ($charCode - $group[0]) + $group[2];
            }
        }

        $record = $this->_record;
        $reader = $record->getFile()->getReader();

        // endCode offset
        $offset = $record->getOffset() + 16 + ($this->_lastGroup * 12);
        $reader->reset($offset, (20 * 3) * 12);

        for ($nGroups = $this->getNGroups(); $this->_lastGroup < $nGroups;) {
            $group = [$reader->readUInt32(), $reader->readUInt32(), $reader->readUInt32()];
            $this->_groups[] = $group;
            $this->_lastGroup++;

            if ($charCode >= $group[0] && $charCode <= $group[1]) {
                return ($charCode - $group[0]) + $group[2];
            }
        }

        return 0;
    }

    /**
     * Get all character code to glyph id mappings.
     *
     * @return array<int, int> The key is the unicode point and the value the glyph id.
     */
    public function getAll()
    {
        $nGroups = $this->getNGroups();
        if ($this->_lastGroup < $nGroups) {
            $record = $this->_record;
            $reader = $record->getFile()->getReader();

            // endCode offset
            $offset = $record->getOffset() + 16 + ($this->_lastGroup * 12);
            $reader->reset($offset, (20 * 3) * 12);

            for ($nGroups = $this->getNGroups(); $this->_lastGroup < $nGroups;) {
                $group = [$reader->readUInt32(), $reader->readUInt32(), $reader->readUInt32()];
                $this->_groups[] = $group;
                $this->_lastGroup++;
            }
        }

        $result = [];
        foreach ($this->_groups AS list($from, $to, $offset)) {
            for ($current = $from;$current <= $to; $current++) {
                $value = ($current - $from) + $offset;
                $result[$current] = $value;
            }
        }

        return $result;
    }
}
