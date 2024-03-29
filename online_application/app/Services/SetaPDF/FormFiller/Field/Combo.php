<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Combo.php 1855 2023-04-28 10:34:15Z jan.slabon $
 */

/**
 * A combo box
 * 
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_FormFiller_Field_Combo
extends SetaPDF_FormFiller_Field_Choice_AbstractChoice
implements SetaPDF_FormFiller_Field_FieldInterface, SetaPDF_FormFiller_Field_AppearanceValueCallbackInterface
{
    /**
     * A callback that may change the appearance value (e.g. format a number)
     *
     * @var callback
     */
    protected $_appearanceValueCallback = [SetaPDF_FormFiller_Field_Formatter::class, 'format'];

    /**
     * Get the current value
     *
     * @param string $encoding The output encoding
     * @return string|null If no value is selected null will be returned otherwise a string.
     */
    public function getValue($encoding = 'UTF-8')
    {
        /**
         * We have to get the V entry from the dictionary holding the T entry, because
         * some documents have corrupted /V entries in their terminal fields (the
         * entries in a Kids array)
         * 
         * This way, we make sure, that the V entry is bound to the dictionary in
         * which the name (T) is defined.
         */
        $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
        if (!$tObject->offsetExists('V')) {
            return null;
        }
        
        $value = $tObject->offsetGet('V')->getValue()->ensure()->getValue();
        
        return SetaPDF_Core_Encoding::convertPdfString($value, $encoding);
    }
    
    /**
     * Returns the default value of the field.
     *
     * This value is used if the form is reset
     *
     * @param string $encoding
     * @return null|string
     * @see SetaPDF_FormFiller_Field_FieldInterface::getDefaultValue()
     */
    public function getDefaultValue($encoding = 'UTF-8')
    {
        $dv = SetaPDF_Core_Type_Dictionary_Helper::resolveAttribute($this->_fieldDictionary, 'DV');
        if (!$dv) {
            return null;
        } 
        
        $defaultValue = $dv->getValue();
        return SetaPDF_Core_Encoding::convertPdfString($defaultValue, $encoding);
    }

    /**
     * Set the default value of the field.
     *
     * @param null|string $value
     * @param string $encoding
     */
    public function setDefaultValue($value, $encoding = 'UTF-8')
    {
        $this->_checkPermission(SetaPDF_Core_SecHandler::PERM_MODIFY);

        if ($value === null) {
            $dict = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'DV');
            if ($dict) {
                $dict->offsetUnset('DV');
            }

            $this->_fields->forwardValueToRelated($value, $this, $encoding, 'setDefaultValue');
            return;
        }

        $originalValue = $value;
        $options = $this->getOptions($encoding);

        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException(
                'Argument of type ' . gettype($value) . ' not allowed.'
            );
        }

        // Check for an entry with this export value
        if (is_string($value)) {
            foreach ($options AS $key => $option) {
                if ($option['exportValue'] === $value) {
                    $value = $key;
                    break;
                }
            }
        }

        if (is_string($value) && !$this->isEditable()) {
            throw new InvalidArgumentException(
                'This combo box is not editable. You will need to pass the ' .
                'numeric index of a related option as an integer to this method'
            );
        }

        $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
        if (is_int($value)) {
            if (!array_key_exists($value, $options)) {
                throw new InvalidArgumentException(
                    sprintf('Unknown option (%s).', $value)
                );
            }

            $value = $this->_exportValues[$value];
            $utf16value = "\xFE\xFF" . SetaPDF_Core_Encoding::convertPdfString($value, 'UTF-16BE');

        } else {
            // Editable

            // Remove this entry
            $tObject->offsetUnset('I');

            // Convert value to UTF-16BE
            $value = SetaPDF_Core_Encoding::convert($value, $encoding, 'UTF-16BE');

            $value = str_replace([
                // replace line breaks and tab with spaces
                "\x00\x0d\x00\x0a",
                "\x00\x0d",
                "\x00\x0a",
                // tab to space
                "\x00\x09"
            ], "\x00\x20", $value);

            $value = "\xFE\xFF" . $value;
            $utf16value = $value;
        }

        $currentValue = $this->getDefaultValue('UTF-16BE');
        if ($currentValue === $utf16value && $this->_fields->isForwardValueActive() === false) {
            return;
        }

        $tObject->offsetSet('DV', new SetaPDF_Core_Type_String($value));

        $this->_fields->forwardValueToRelated($originalValue, $this, $encoding, 'setDefaultValue');
    }
    
    /**
     * Get the visible value of the current selected option
     * 
     * @param string $encoding
     * @return string
     */
    public function getVisibleValue($encoding = 'UTF-8')
    {
        $options = $this->getOptions($encoding);
        
        $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
        if ($tObject->offsetExists('I')) {
            $offset = $tObject->offsetGet('I')->getValue()->ensure();
            if ($offset->offsetExists(0)) {
                $offset = $offset->offsetGet(0)->ensure()->getValue();
                return $options[$offset]['visibleValue'];
            }
        }
        
        $value = $this->getValue($encoding);
        foreach ($options AS $option) {
            if ($option['exportValue'] === $value) {
                return $option['visibleValue'];
            }
        }

        return $value;
    }

    /**
     * Set the fields value / Selects the option
     *
     * The value could be an export value of an option or the numeric index
     * of an option, received by getOptions().
     * If the field is marked as an editable text field, it is also possible
     * to pass a string.
     * 
     * @param string|integer $value
     * @param string $encoding
     * @throws InvalidArgumentException
     */
    public function setValue($value, $encoding = 'UTF-8')
    {
        $this->_checkPermission();
        
        $originalValue = $value;
        $options = $this->getOptions($encoding);

        if ($value === null) {
            $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
            $tObject->offsetUnset('V');
            $tObject->offsetUnset('I');
            
        } else {
            if (!is_string($value) && !is_int($value)) {
                throw new InvalidArgumentException(
                    'Argument of type ' . gettype($value) . ' not allowed.'
                );
            }
            
            // Check for an entry with this export value
            if (is_string($value)) {
                foreach ($options AS $key => $option) {
                    if ($option['exportValue'] === $value) {
                        $value = $key;
                        break;      
                    }
                }
            }
            
            if (is_string($value) && !$this->isEditable()) {
                throw new InvalidArgumentException(
                    'This combo box is not editable. You will need to pass the numeric index of ' .
                    'a related option as an integer or an export value to this method'
                );
            }
            
            $tObject = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_fieldDictionary, 'T');
            if (is_int($value)) {
                if (!array_key_exists($value, $options)) {
                    throw new InvalidArgumentException(
                        sprintf('Unknown option (%s).', $value)
                    );
                }
                /**
                 * normally the I entry is not needed for a combo box, but
                 * Acrobat adds it. This is not conform to the specs for that issue: 
                 * <cite>If the items identified by this entry (I) differ from 
                 * those in the V entry of the field dictionary ([...]), the V
                 * entry shall be used.</cite>
                 */ 
                $tObject->offsetSet('I',
                    new SetaPDF_Core_Type_Array([
                        new SetaPDF_Core_Type_Numeric($value)]
                    )
                );
                
                $value = $this->_exportValues[$value];
            
            } else {
                // Editable
                
                // Remove this entry
                $tObject->offsetUnset('I');
                
                // Convert value to UTF-16BE
                $value = SetaPDF_Core_Encoding::convert($value, $encoding, 'UTF-16BE');

                $value = str_replace([
                    // replace line breaks and tab with spaces
                    "\x00\x0d\x00\x0a",
                    "\x00\x0d",
                    "\x00\x0a",
                    // tab to space
                    "\x00\x09"
                ], "\x00\x20", $value);
                
                $value = "\xFE\xFF" . $value;
            }
            
            $tObject->offsetSet('V', new SetaPDF_Core_Type_String($value));
        }
        
        $this->recreateAppearance();
        
        $this->_fields->forwardValueToRelated($originalValue, $this, $encoding);
    }

    /**
     * Set the appearance value callback (to e.g. format a number).
     *
     * The callback will be called with 2 arguments:
     *  1. A reference to the field instance
     *  2. The requested encoding
     *
     * It needs to return a value in the specified encoding (internal calls need UTF-16BE throughout).
     *
     * @param callback $callback
     */
    public function setAppearanceValueCallback($callback)
    {
        $this->_appearanceValueCallback = $callback;
    }

    /**
     * Get the appearance value.
     *
     * @param string $encoding
     * @return string
     */
    public function getAppearanceValue($encoding = 'UTF-8')
    {
        if (!is_callable($this->_appearanceValueCallback)) {
            return $this->getVisibleValue($encoding);
        }

        return call_user_func($this->_appearanceValueCallback, $this, $encoding);
    }

    /**
     * Recreate or creates the appearance of the form field
     */
    public function recreateAppearance()
    {
        $value = $this->getAppearanceValue('UTF-16BE');

        /**
         * INFO: A list box is rerendered by default by the reader.
         *         So the rendered appearance is only checkable/viewable by
         *      flattening the field to the pages content stream.
         */
        $canvas = $this->_recreateAppearance();
        if ($value) {
            $annotation = $this->getAnnotation();

            list($borderWidth, $borderStyle) = $this->_getBorderWidthAndStyle();

            $borderDoubled = (
                $borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::BEVELED ||
                $borderStyle === SetaPDF_Core_Document_Page_Annotation_BorderStyle::INSET
            );

            $width = $annotation->getWidth();
            $height = $annotation->getHeight();
    
            $font = $this->getAppearanceFont();

            $lineHeightFactor = $this->getLineHeightFactor();
            
            $left = SetaPDF_Core::isZero($borderWidth)
                  ? 2
                  : $borderWidth * ($borderDoubled ? 4 : 2);
            
            // Calculate Font Size
            $fontSize = $this->getAppearanceFontSize();
            if (SetaPDF_Core::isZero($fontSize)) {
                // INFO: The trigger is not taken into account here, because
                //     it is displayed with a fixed width in the viewer/reader. 
                
                // 1.4 was resolved by simply testing...
                $maxSize = ($height
                           - ($borderWidth > 0
                               ? $borderWidth * ($borderDoubled ? 4 : 2) 
                               : 0
                           )) / 1.4;
                $maxWidth = $width  
                          - ($borderWidth * ($borderDoubled ? 8 : 4))
                          - (SetaPDF_Core::isZero($borderWidth) ? 4 : 0);
                $glyphWidth = $font->getGlyphsWidth($value) / 1000;
                $fontSize = min($maxWidth / ($glyphWidth ?: 1), $maxSize);
                $fontSize = round($fontSize, 4);
                $fontSize = max($fontSize, 4);
            }
            
            $leading = $fontSize * $lineHeightFactor;
            
            $top = $height / 2;  
            $top -= $leading / 2;
            $top -= ($fontSize * $font->getDescent() / 1000);
            
            $offset = $borderWidth * ($borderDoubled ? 2 : 1);

            $canvas->write(' /Tx BMC');
            $canvas->saveGraphicState();

            $canvas->path()->rect(
                $offset,
                $offset,
                $width - $offset * 2,
                $height - $offset * 2
            )->clip()->endPath();

            $canvas->text()
                ->begin()
                ->setFont($font, $fontSize);

            $colorSpace = $this->getAppearanceTextColorSpace();
            if ($colorSpace !== null) {
                $canvas->setNonStrokingColorSpace($colorSpace);
            }

            $this->getAppearanceTextColor()->draw($canvas, false);
            $canvas->text()->moveToNextLine($left, $top);

            // Show Text
            $charCodes = $font->getCharCodes($value);
            $canvas->text()
                ->showText($charCodes)
                ->end();

            $canvas->restoreGraphicState();
            $canvas->write(' EMC');
        }

        $this->_appearanceCreated = true;
    }
    
    /**
     * Checks if the combo box shall include an editable text box as well to a drop-down list
     * 
     * @return boolean
     */
    public function isEditable()
    {
        return $this->isFieldFlagSet(SetaPDF_FormFiller_Field_Flags::EDIT);
    }
    
    /**
     * Sets if the combo box shall include an editable text box as well to a drop-down list
     * 
     * @param boolean $editable
     * @param boolean $setDefaultValue
     * @return void
     */
    public function setEditable($editable = true, $setDefaultValue = true)
    {
        // TODO: Should change value to null, if the value is not in the options list
        if ($editable === false && $setDefaultValue == true) {
            $dv = $this->getDefaultValue('UTF-16BE');
            $this->setValue($dv, 'UTF-16BE');
        }
        $this->setFieldFlags(SetaPDF_FormFiller_Field_Flags::EDIT, $editable);
    }
}