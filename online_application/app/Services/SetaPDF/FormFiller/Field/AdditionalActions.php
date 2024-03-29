<?php
/**
 * This file is part of the SetaPDF-FormFiller Component
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: AdditionalActions.php 1847 2023-04-25 08:21:40Z jan.slabon $
 */

/**
 * Class representing a form fields additional-actions dictionary
 *
 * @copyright  Copyright (c) 2022 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_FormFiller
 * @subpackage Field
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_FormFiller_Field_AdditionalActions
{
    /**
     * The annotation instance
     *
     * @var SetaPDF_FormFiller_Field_AbstractField
     */
    protected $_field;

    /**
     * The constructor.
     *
     * @param SetaPDF_FormFiller_Field_AbstractField $field
     */
    public function __construct(SetaPDF_FormFiller_Field_AbstractField $field)
    {
        $this->_field = $field;
    }

    /**
     * Release memory/cycled references.
     */
    public function cleanUp()
    {
        $this->_field = null;
    }

    /**
     * Get the additional actions dictionary.
     *
     * @param bool $create Pass true to automatically create the dictionary
     * @return null|SetaPDF_Core_Type_Dictionary
     */
    public function getDictionary($create = false)
    {
        $dictionary = SetaPDF_Core_Type_Dictionary_Helper::resolveDictionaryByAttribute($this->_field->getFieldDictionary(), 'T');
        $aa = SetaPDF_Core_Type_Dictionary_Helper::getValue($dictionary, 'AA');

        if (!$aa instanceof SetaPDF_Core_Type_Dictionary) {
            if ($create === false) {
                return null;
            }

            $dictionary->offsetSet('AA', $aa = new SetaPDF_Core_Type_Dictionary());
        }

        return $aa;
    }

    /**
     * Get the JavaScript action that shall be performed when the user modifies a character in a text field or
     * combo box or modifies the selection in a scrollable list box.
     *
     * This action may check the added text for validity and reject or modify it.
     *
     * @return null|SetaPDF_Core_Document_Action_JavaScript
     * @throws SetaPDF_Core_Type_Exception
     */
    public function getKeystroke()
    {
        return $this->_getAction('K');
    }

    /**
     * Set the JavaScript action that shall be performed when the user modifies a character in a text field or
     * combo box or modifies the selection in a scrollable list box.
     *
     * This action may check the added text for validity and reject or modify it.
     *
     * @param SetaPDF_Core_Document_Action_JavaScript|string $action
     * @return SetaPDF_FormFiller_Field_AdditionalActions
     * @throws SetaPDF_Core_Type_Exception
     */
    public function setKeystroke($action)
    {
        $this->_setAction('K', $action);

        return $this;
    }

    /**
     * Get the JavaScript action that shall be performed before the field is formatted to display its value.
     *
     * This action may modify the field’s value before formatting.
     *
     * @return null|SetaPDF_Core_Document_Action_JavaScript
     * @throws SetaPDF_Core_Type_Exception
     */
    public function getFormat()
    {
        return $this->_getAction('F');
    }

    /**
     * Set the JavaScript action that shall be performed before the field is formatted to display its value.
     *
     * This action may modify the field’s value before formatting.
     *
     * @param SetaPDF_Core_Document_Action_JavaScript|string $action
     * @return SetaPDF_FormFiller_Field_AdditionalActions
     * @throws SetaPDF_Core_Type_Exception
     */
    public function setFormat($action)
    {
        $this->_setAction('F', $action);

        return $this;
    }

    /**
     * Get the JavaScript action that shall be performed when the field’s value is changed.
     *
     * This action may check the new value for validity.
     *
     * @return null|SetaPDF_Core_Document_Action_JavaScript
     * @throws SetaPDF_Core_Type_Exception
     */
    public function getValidate()
    {
        return $this->_getAction('V');
    }

    /**
     * Set the JavaScript action that shall be performed when the field’s value is changed.
     *
     * This action may check the new value for validity.
     *
     * @param SetaPDF_Core_Document_Action_JavaScript|string $action
     * @return SetaPDF_FormFiller_Field_AdditionalActions
     * @throws SetaPDF_Core_Type_Exception
     */
    public function setValidate($action)
    {
        $this->_setAction('V', $action);

        return $this;
    }

    /**
     * Get the action that shall be performed when the mouse button is released inside the annotation’s active area.
     *
     * @return null|SetaPDF_Core_Document_Action_JavaScript
     * @throws SetaPDF_Core_Type_Exception
     */
    public function getCalculate()
    {
        return $this->_getAction('C');
    }

    /**
     * Set the action that shall be performed when the mouse button is released inside the annotation’s active area.
     *
     * @param SetaPDF_Core_Document_Action_JavaScript|string $action
     * @return SetaPDF_FormFiller_Field_AdditionalActions
     * @throws SetaPDF_Core_Type_Exception
     */
    public function setCalculate($action)
    {
        $this->_setAction('C', $action);

        return $this;
    }

    /**
     * Get the action.
     *
     * @param string $name
     * @param boolean $instance
     * @return null|SetaPDF_Core_Document_Action_JavaScript
     * @throws SetaPDF_Core_Type_Exception
     */
    protected function _getAction($name, $instance = true)
    {
        $dictionary = $this->getDictionary();
        if ($dictionary === null) {
            return null;
        }

        $action = SetaPDF_Core_Type_Dictionary_Helper::getValue($dictionary, $name);
        if ($action === null) {
            return null;
        }

        if ($instance) {
            return SetaPDF_Core_Document_Action::byObjectOrDictionary($action);
        }

        return $action;
    }

    /**
     * Set the action.
     *
     * @param string $name
     * @param string|SetaPDF_Core_Document_Action_JavaScript|SetaPDF_Core_Type_Dictionary|SetaPDF_Core_Type_IndirectObjectInterface $javaScriptAction
     * @throws SetaPDF_Core_Type_Exception
     */
    protected function _setAction($name, $javaScriptAction)
    {
        if ($javaScriptAction !== null) {
            if (!($javaScriptAction instanceof SetaPDF_Core_Document_Action_JavaScript)) {
                $javaScriptAction = new SetaPDF_Core_Document_Action_JavaScript($javaScriptAction);
            }

            /** @var SetaPDF_Core_Type_Dictionary $dictionary */
            $dictionary = $this->getDictionary(true);
            $dictionary->offsetSet($name, $javaScriptAction->getIndirectObject(
                $this->_field->getFields()->getFormFiller()->getDocument())
            );
        } else {
            $action = $this->_getAction($name, false);
            if ($action === null) {
                return;
            }

            $dictionary = $this->getDictionary();
            $dictionary->offsetUnset($name);
        }
    }
}
