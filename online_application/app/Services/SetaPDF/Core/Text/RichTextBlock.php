<?php
/**
 * This file is part of the SetaPDF-Stamper Component
 *
 * @copyright  Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Text
 * @license    https://www.setasign.com/ Commercial
 * @version    $Id: Block.php 1803 2023-01-20 10:41:31Z jan.slabon $
 */

/**
 * Class representing a rich-text block which can be drawn onto a canvas object
 *
 * A rich-text block allows you to use a subset of HTML and CSS to style the text.
 * Following HTML tags are interpreted as you know from HTML:
 * - `<b>` / `<strong>` for bold text
 * - `<i>` / `<em>` for italic text
 * - `<u>` for underline text
 * - `<sup>` for superscript
 * - `<sub>` for subscript
 * - `<br>` / `</br>` for a line-break
 *
 * You can use any other HTML tag such as `<span>` or `<div>`, too. Internally these tags are only parsed
 * for their `style` attibute.
 *
 * You can use the `style` attribute to define CSS styles for an element to any tag.
 * Following CSS styles are supported:
 * - `font-family: (string)`
 * - `font-size: (float|integer)(pt|%)`
 * - `color: #RRGGBB` hexadecimal notation
 * - `line-height: (float|integer)(%)` unitless or percentual value
 *
 * By default, the instance uses the standard font {@link SetaPDF_Core_Font_Standard_Helvetica Helvetica} and
 * loads the different styles automatically by a predefined font-loader callable.
 *
 * To use individual fonts and make use of font subsets you have to pass your own font-loader which is a callable with
 * following signature:
 * ```
 * function (
 *     SetaPDF_Core_Document $document,
 *     string $fontFamily, # The font family as defined in the font-family property.
 *     string $fontStyle   # An empty string = normal, 'B' = bold, 'I' = italic, 'BI' = bold+italic
 * ): ?SetaPDF_Core_Font_FontInterface`
 * ```
 *
 * The font instances created in this callable are bound to the document instance, and you need to take care of caching
 * the instances appropriately to avoid an unneeded overhead. You also should make sure that the callable shares the
 * font instances through different rich-text block instances.
 *
 * A font-loader for e.g. _DejaVuSans_ could look like:
 *
 * ```php
 * $loadedFonts = [];
 * $fontLoader = function (SetaPDF_Core_Document $document, $fontFamily, $fontStyle) use (&$loadedFonts) {
 *     $cacheKey = $document->getInstanceIdent() . '_' . $fontFamily . '_' . $fontStyle;
 *     $font = null;
 *     if (!array_key_exists($cacheKey, $loadedFonts)) {
 *         if ($fontFamily === 'DejaVuSans' && $fontStyle === '') {
 *             $font = new SetaPDF_Core_Font_Type0_Subset($document, 'path/to/DejaVuSans.ttf');
 *         } elseif ($fontFamily === 'DejaVuSans' && $fontStyle === 'B') {
 *             $font = new SetaPDF_Core_Font_Type0_Subset($document, 'path/to/DejaVuSans-Bold.ttf');
 *         } elseif ($fontFamily === 'DejaVuSans' && $fontStyle === 'I') {
 *             $font = new SetaPDF_Core_Font_Type0_Subset($document, 'path/to/DejaVuSans-Oblique.ttf');
 *         } elseif ($fontFamily === 'DejaVuSans' && $fontStyle === 'BI') {
 *             $font = new SetaPDF_Core_Font_Type0_Subset($document, 'path/to/DejaVuSans-BoldOblique.ttf');
 *         }
 *         $loadedFonts[$cacheKey] = $font;
 *     }
 *     return $loadedFonts[$cacheKey];
 * };
 * ```
 * and can be registered by the {@link SetaPDF_Core_Text_RichTextBlock::registerFontLoader() `registerFontLoader()`}
 * method:
 * ```php
 * $richTextBlock->registerFontLoader($fontLoader);
 * ```
 * In the same step you should set _Frutiger_ as the default font-family:
 * ```php
 * $richTextBlock->setDefaultFontFamily('DejaVuSans');
 * ```
 *
 * @copyright  Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @category   SetaPDF
 * @package    SetaPDF_Core
 * @subpackage Text
 * @license    https://www.setasign.com/ Commercial
 */
class SetaPDF_Core_Text_RichTextBlock
{
    /**
     * @var string
     */
    protected $defaultFontFamily = 'Helvetica';

    /**
     * @var int|float
     */
    protected $defaultFontSize = 12;

    /**
     * @var int|float|string|array|SetaPDF_Core_Type_Array|SetaPDF_Core_DataStructure_Color
     */
    protected $defaultTextColor = '#000000';

    /**
     * @var int|float
     */
    protected $defaultLineHeight = 1.21;

    /**
     * @var callable A callback with the signature `function (SetaPDF_Core_Document $document, string $fontFamily, string $fontStyle): ?SetaPDF_Core_Font_FontInterface`
     */
    private $fontLoader;

    /**
     * If true an error will be thrown if a tag or style isn't supported.
     *
     * @var bool
     */
    protected $strict = false;

    /**
     * @var null|int|float
     */
    protected $width;

    /**
     * @var null|int|float Used for caching if no width is given.
     */
    protected $dynamicWidth;

    /**
     * @var string
     * @see SetaPDF_Core_Text::ALIGN_*
     */
    protected $align = SetaPDF_Core_Text::ALIGN_LEFT;

    /**
     * @var DOMElement
     */
    protected $bodyNode;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var null|array
     */
    protected $calculatedLines;

    /**
     * @var SetaPDF_Core_Document
     */
    protected $document;

    /**
     * @var SetaPDF_Core_DataStructure_Color|null
     */
    protected $backgroundColor;

    /**
     * @var SetaPDF_Core_DataStructure_Color|null
     */
    protected $borderColor;

    /**
     * @var int|float
     */
    protected $borderWidth = 0;

    /**
     * @var int|float
     */
    protected $paddingTop = 0;

    /**
     * @var int|float
     */
    protected $paddingRight = 0;

    /**
     * @var int|float
     */
    protected $paddingBottom = 0;

    /**
     * @var int|float
     */
    protected $paddingLeft = 0;

    public function __construct(SetaPDF_Core_Document $document)
    {
        if (!class_exists(DOMDocument::class)) {
            throw new RuntimeException('Missing dependency "ext-dom"!');
        }

        if (!class_exists(IntlBreakIterator::class)) {
            throw new RuntimeException('Missing dependency "ext-intl"!');
        }

        $this->document = $document;
    }

    /**
     * If true an error will be thrown if a tag or style isn't supported.
     *
     * @param bool $strict
     * @return void
     */
    public function setStrict($strict = true)
    {
        $this->strict = (bool) $strict;
    }


    /**
     * Registers the default font-loader which handles the standard font Helvetica.
     *
     * @param array $loadedFonts Memorized loaded fonts. If you're using multiple RichTextBlocks these should share the **same** $loadedFonts array.
     * @return void
     */
    public function registerDefaultFontLoader(&$loadedFonts = [])
    {
        $this->fontLoader = static function (SetaPDF_Core_Document $document, $fontFamily, $fontStyle) use (&$loadedFonts) {
            $fontFamily = trim($fontFamily, '\'"');
            $cacheKey = $document->getInstanceIdent() . '_' . $fontFamily . '_' . $fontStyle;
            if (!array_key_exists($cacheKey, $loadedFonts)) {
                $font = null;
                if (strcasecmp($fontFamily, 'Helvetica') === 0) {
                    if ($fontStyle === '') {
                        $font = SetaPDF_Core_Font_Standard_Helvetica::create($document);
                    } elseif ($fontStyle === 'B') {
                        $font = SetaPDF_Core_Font_Standard_HelveticaBold::create($document);
                    } elseif ($fontStyle === 'I') {
                        $font = SetaPDF_Core_Font_Standard_HelveticaOblique::create($document);
                    } elseif ($fontStyle === 'BI') {
                        $font = SetaPDF_Core_Font_Standard_HelveticaBoldOblique::create($document);
                    }
                }

                $loadedFonts[$cacheKey] = $font;
            }

            return $loadedFonts[$cacheKey];
        };
    }

    /**
     * Register a font loader.
     *
     * Please note that you MUST cache the results of this callback per document.
     *
     * @param callable $resolveFont A callable with the following signature: `function (SetaPDF_Core_Document $document, string $fontFamily, string $fontStyle): ?SetaPDF_Core_Font_FontInterface`
     * @return void
     * @see registerDefaultFontLoader
     */
    public function registerFontLoader(callable $resolveFont)
    {
        $this->fontLoader = $resolveFont;
    }

    /**
     * @param string $fontFamily
     * @param string $fontStyle
     * @return SetaPDF_Core_Font_FontInterface
     * @throws SetaPDF_Core_Exception
     */
    protected function loadFont($fontFamily, $fontStyle)
    {
        if ($this->fontLoader === null) {
            throw new BadMethodCallException(
                'Missing font loader! Please call registerDefaultFontLoader() or registerFontLoader() first!'
            );
        }
        $font = call_user_func($this->fontLoader, $this->document, $fontFamily, $fontStyle);
        if (!$font instanceof SetaPDF_Core_Font_FontInterface) {
            throw new SetaPDF_Core_Exception(sprintf(
                'Cannot resolve font "%s" with style "%s".',
                $fontFamily,
                $fontStyle
            ));
        }

        return $font;
    }

    /**
     * @param string $fontFamily
     * @return void
     */
    public function setDefaultFontFamily($fontFamily)
    {
        $this->defaultFontFamily = $fontFamily;
        $this->calculatedLines = null;
    }

    /**
     * @return string
     */
    public function getDefaultFontFamily()
    {
        return $this->defaultFontFamily;
    }

    /**
     * @param int|float $fontSize
     * @return void
     */
    public function setDefaultFontSize($fontSize)
    {
        $this->defaultFontSize = $fontSize;
        $this->calculatedLines = null;
    }

    /**
     * @return float|int
     */
    public function getDefaultFontSize()
    {
        return $this->defaultFontSize;
    }

    /**
     * @param int|float|string|array|SetaPDF_Core_Type_Array|SetaPDF_Core_DataStructure_Color $color
     * @return void
     */
    public function setDefaultTextColor($color)
    {
        $this->defaultTextColor = $color;
    }

    /**
     * @return SetaPDF_Core_DataStructure_Color
     */
    public function getDefaultTextColor()
    {
        if (!$this->defaultTextColor instanceof SetaPDF_Core_DataStructure_Color) {
            return SetaPDF_Core_DataStructure_Color::createByComponents($this->defaultTextColor);
        }
        return $this->defaultTextColor;
    }

    /**
     * @param int|float $lineHeight The unitless line-height (e.g 1 or 1.4)
     * @return void
     */
    public function setDefaultLineHeight($lineHeight)
    {
        $this->defaultLineHeight = $lineHeight;
        $this->calculatedLines = null;
    }

    /**
     * @return float|int
     */
    public function getDefaultLineHeight()
    {
        return $this->defaultLineHeight;
    }

    /**
     * Set the width of the rich-text. Padding is not included in this width.
     *
     * @param null|int|float $width
     */
    public function setTextWidth($width)
    {
        $this->width = $width;
        $this->calculatedLines = null;
    }

    /**
     * Returns the width of the rich-text including padding and border.
     *
     * @return int|float
     * @throws SetaPDF_Core_Exception
     */
    public function getWidth()
    {
        return $this->getTextWidth() + $this->paddingLeft + $this->paddingRight + $this->getBorderWidth() * 2;
    }

    /**
     * Returns the width of the text block without padding.
     *
     * @return int|float
     * @throws SetaPDF_Core_Exception
     */
    public function getTextWidth()
    {
        if ($this->width === null) {
            if ($this->calculatedLines === null) {
                $this->calculateLines();
            }
            return $this->dynamicWidth;
        }

        return $this->width;
    }

    /**
     * Returns the height of the text block including padding and border.
     *
     * @return float
     * @throws SetaPDF_Core_Exception
     */
    public function getHeight()
    {
        $lines = $this->calculateLines();
        return array_sum(array_map(function ($line) {
                return $line['maxAscender'] + $line['maxDescender'];
            }, $lines))
            + $this->paddingTop + $this->paddingBottom + $this->getBorderWidth() * 2;
    }

    /**
     * Set the text alignment.
     *
     * @param string $align
     * @see SetaPDF_Core_Text::ALIGN_*
     */
    public function setAlign($align)
    {
        $this->align = $align;
    }

    /**
     * Get the text alignment.
     *
     * @return string
     */
    public function getAlign()
    {
        return $this->align;
    }

    /**
     * Set the background color.
     *
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     * @param SetaPDF_Core_DataStructure_Color|int|float|string|array|SetaPDF_Core_Type_Array $color
     */
    public function setBackgroundColor($color)
    {
        if (!$color instanceof SetaPDF_Core_DataStructure_Color && $color !== null) {
            $color = SetaPDF_Core_DataStructure_Color::createByComponents($color);
        }

        $this->backgroundColor = $color;
    }

    /**
     * Get the background color object.
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * Set the border color.
     *
     * @see SetaPDF_Core_DataStructure_Color::createByComponents()
     * @param SetaPDF_Core_DataStructure_Color|int|float|string|array|SetaPDF_Core_Type_Array $color
     */
    public function setBorderColor($color)
    {
        if (!$color instanceof SetaPDF_Core_DataStructure_Color && $color !== null) {
            $color = SetaPDF_Core_DataStructure_Color::createByComponents($color);
        }

        $this->borderColor = $color;
    }

    /**
     * Get the border color object.
     *
     * If no border color is defined a greyscale black color will be returned.
     *
     * @return null|SetaPDF_Core_DataStructure_Color
     */
    public function getBorderColor()
    {
        if ($this->borderColor === null) {
            $this->borderColor = new SetaPDF_Core_DataStructure_Color_Gray(0);
        }

        return $this->borderColor;
    }

    /**
     * Set the border width.
     *
     * @param int|float $borderWidth
     */
    public function setBorderWidth($borderWidth)
    {
        $this->borderWidth = (float) $borderWidth;
    }

    /**
     * Get the border width.
     *
     * @return int|float
     */
    public function getBorderWidth()
    {
        return $this->borderWidth;
    }


    /**
     * Set the padding.
     *
     * @param int|float $padding
     */
    public function setPadding($padding)
    {
        $padding = (float) $padding;
        $this->paddingTop = $padding;
        $this->paddingRight = $padding;
        $this->paddingBottom = $padding;
        $this->paddingLeft = $padding;
    }

    /**
     * Set the top padding.
     *
     * @param int|float $paddingTop
     */
    public function setPaddingTop($paddingTop)
    {
        $this->paddingTop = (float) $paddingTop;
    }

    /**
     * Get the top padding.
     *
     * @return int|float
     */
    public function getPaddingTop()
    {
        return $this->paddingTop;
    }

    /**
     * Set the right padding.
     *
     * @param int|float $paddingRight
     */
    public function setPaddingRight($paddingRight)
    {
        $this->paddingRight = (float) $paddingRight;
    }

    /**
     * Get the right padding.
     *
     * @return int|float
     */
    public function getPaddingRight()
    {
        return $this->paddingRight;
    }

    /**
     * Set the bottom padding.
     *
     * @param int|float $paddingBottom
     */
    public function setPaddingBottom($paddingBottom)
    {
        $this->paddingBottom = (float) $paddingBottom;
    }

    /**
     * Get the bottom padding.
     *
     * @return int|float
     */
    public function getPaddingBottom()
    {
        return $this->paddingBottom;
    }

    /**
     * Set the left padding.
     *
     * @param int|float $paddingLeft
     */
    public function setPaddingLeft($paddingLeft)
    {
        $this->paddingLeft = (float) $paddingLeft;
    }

    /**
     * Get the left padding.
     *
     * @return int|float
     */
    public function getPaddingLeft()
    {
        return $this->paddingLeft;
    }

    /**
     * Set the rich-text.
     *
     * The text is cleaned-up and passed to a body node in a raw HTML template which is then loaded by
     * {@link https://www.php.net/manual/en/domdocument.loadhtml.php DOMDocument::loadHTML()} method.
     * Entity loading, network access and error handling is disabled by using `LIBXML_NONET | LIBXML_NOERROR` as
     * the `$options` parameter. For PHP < 8 also `libxml_disable_entity_loader()` is used to disable entity loading.
     *
     * @param string $text An UTF-8 string
     * @param string $locale INTL locale used for the break behaviour
     * @return void
     */
    public function setText($text, $locale = 'en_US')
    {
        $this->locale = $locale;
        $this->calculatedLines = null;

        $text = preg_replace('~\s*[\r\n]+\s*~u', "\n", $text);
        $text = preg_replace('~[\t\r\n]+~u', ' ', $text);

        $document = new DOMDocument('1.0', 'UTF-8');
        /** @noinspection HtmlRequiredLangAttribute */
        /** @noinspection HtmlRequiredTitleElement */
        $html = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>' . $text . '</body></html>';
        if (PHP_VERSION_ID < 80000) {
            $oldEntityLoaderValue = libxml_disable_entity_loader();
        }

        // LIBXML_NOERROR has no meaning in PHP < 7.2, so we need to silence the errors that way, too
        $oldInternalErrorsValue = libxml_use_internal_errors(true);
        try {
            if (!$document->loadHTML($html, LIBXML_NONET | LIBXML_NOERROR)) {
                throw new InvalidArgumentException('Cannot parse rich text!');
            }
        } finally {
            if (isset($oldEntityLoaderValue)) {
                libxml_disable_entity_loader($oldEntityLoaderValue);
            }

            libxml_clear_errors();
            libxml_use_internal_errors($oldInternalErrorsValue);
        }

        $xpath = new DOMXPath($document);
        $bodyNode = $xpath->query('//html/body')->item(0);
        if (!($bodyNode instanceof DOMElement)) {
            throw new InvalidArgumentException('Couldn\'t find body node');
        }

        $this->bodyNode = $bodyNode;
    }

    /**
     * Split the text into word blocks
     *
     * @return array
     * @throws SetaPDF_Core_Exception
     */
    protected function parseText()
    {
        if ($this->bodyNode === null) {
            return [];
        }

        return $this->parseChilds(
            $this->bodyNode,
            $this->defaultFontFamily,
            $this->defaultFontSize,
            $this->getDefaultTextColor(),
            '',
            $this->defaultLineHeight,
            []
        );
    }

    /**
     * @param DOMNode $node
     * @param string $fontFamily
     * @param int|float $fontSize
     * @param SetaPDF_Core_DataStructure_Color $color
     * @param string $fontStyle
     * @param int|float $lineHeight
     * @param array $fontDecoration
     * @return array
     * @throws SetaPDF_Core_Exception
     */
    protected function parseChilds(
        DOMNode $node,
        $fontFamily,
        $fontSize,
        SetaPDF_Core_DataStructure_Color $color,
        $fontStyle,
        $lineHeight,
        array $fontDecoration
    ) {
        $result = [];
        foreach ($node->childNodes as $childNode) {
            $nodeResult = $this->parseNode(
                $childNode,
                $fontFamily,
                $fontSize,
                $color,
                $fontStyle,
                $lineHeight,
                $fontDecoration
            );
            if (!is_array($nodeResult)) {
                continue;
            }

            if (!isset($nodeResult['type'])) {
                foreach ($nodeResult as $singleNodeResult) {
                    $result[] = $singleNodeResult;
                }
            } else {
                $result[] = $nodeResult;
            }
        }
        return $result;
    }

    /**
     * @param DOMNode $node
     * @param string $fontFamily
     * @param int|float $fontSize
     * @param SetaPDF_Core_DataStructure_Color $color
     * @param string $fontStyle
     * @param int|float $lineHeight
     * @param array $fontDecoration
     * @return array
     * @throws SetaPDF_Core_Exception
     */
    protected function parseNode(
        DOMNode $node,
        $fontFamily,
        $fontSize,
        SetaPDF_Core_DataStructure_Color $color,
        $fontStyle,
        $lineHeight,
        array $fontDecoration
    ) {
        if ($node->nodeType === XML_TEXT_NODE) {
            return [
                'type' => 'text',
                'text' => $node->textContent,
                'font' => $this->loadFont($fontFamily, $fontStyle),
                'fontSize' => $fontSize,
                'color' => $color,
                'lineHeight' => $lineHeight,
                'fontDecoration' => $fontDecoration
            ];
        }

        if ($node->nodeType !== XML_ELEMENT_NODE) {
            return [];
        }

        $styleAttribute = $node->attributes->getNamedItem('style');
        $styleArray = [];
        if ($styleAttribute !== null) {
            // remove css comments
            $style = preg_replace('~/\*.*\*/~U', '', $styleAttribute->value);
            foreach (explode(';', $style) as $styleEntry) {
                $styleEntry = trim($styleEntry);
                if ($styleEntry === '') {
                    continue;
                }
                $styleEntries = explode(':' , $styleEntry);
                if (count($styleEntries) !== 2) {
                    if ($this->strict) {
                        throw new SetaPDF_Core_Exception(sprintf('Cannot resolve style "%s"', $style));
                    }
                    continue;
                }
                $styleArray[trim($styleEntries[0])] = trim($styleEntries[1]);
            }
            unset($style);

            foreach ($styleArray as $styleKey => $styleValue) {
                switch ($styleKey) {
                    case 'font-family':
                        $fontFamily = $styleValue;
                        break;

                    case 'font-size':
                        if (preg_match('~^(\d+(\.\d+)?)(?:\s*(pt|%))?$~i', $styleValue, $match) === 1) {
                            $factor = isset($match[3]) && $match[3] === '%' ? $fontSize/100 : 1;
                            if (isset($match[2])) {
                                $fontSize = (float)$match[1] * $factor;
                            } else {
                                $fontSize = (int)$match[1] * $factor;
                            }
                        } else {
                            // remove invalid/unknown styles
                            unset($styleArray[$styleKey]);
                            if ($this->strict) {
                                throw new SetaPDF_Core_Exception(sprintf('Unknown font size format "%s"', $styleValue));
                            }
                            continue 2;
                        }

                        break;

                    case 'line-height':
                        if (preg_match('~^(\d+(\.\d+)?)\s*(%)?$~i', $styleValue, $match) === 1) {
                            if (isset($match[2])) {
                                $lineHeight = (float)$match[1];
                            } else {
                                $lineHeight = (int)$match[1];
                            }

                            if (isset($match[3]) && $match[3] === '%') {
                                $lineHeight /= 100;
                            }
                        } else {
                            // remove invalid/unknown styles
                            unset($styleArray[$styleKey]);
                            if ($this->strict) {
                                throw new SetaPDF_Core_Exception(sprintf('Unknown line height format "%s"', $styleValue));
                            }
                            continue 2;
                        }
                        break;

                    case 'color':
                        try {
                            $color = SetaPDF_Core_DataStructure_Color::createByComponents($styleValue);
                        } catch (InvalidArgumentException $e) {
                            unset($styleArray[$styleKey]);
                            if ($this->strict) {
                                throw new SetaPDF_Core_Exception(
                                    sprintf('Invalid color value "%s".', $styleValue)
                                );
                            }
                        }
                        break;

                    default:
                        // remove invalid/unknown styles
                        unset($styleArray[$styleKey]);
                        if ($this->strict) {
                            throw new SetaPDF_Core_Exception(sprintf('Unsupported style "%s"', $styleKey));
                        }
                        continue 2;
                }
            }
        }

        /**
         * @var DOMElement $node
         */
        switch ($node->localName) {
            case 'b':
            case 'strong':
                $newStyle = 'B';
            // fall through
            case 'i':
            case 'em':
                if (!isset($newStyle)) {
                    $newStyle = 'I';
                }

                if (strpos($fontStyle, $newStyle) === false) {
                    $fontStyle .= $newStyle;

                    if ($fontStyle === 'IB') {
                        $fontStyle = 'BI';
                    }
                }

                return $this->parseChilds($node, $fontFamily, $fontSize, $color, $fontStyle, $lineHeight, $fontDecoration);

            case 'u':
                $fontDecoration['underline'] = $fontSize;
                return $this->parseChilds($node, $fontFamily, $fontSize, $color, $fontStyle, $lineHeight, $fontDecoration);

            case 'sup':
                $fontDecoration['yOffset'] = (isset($fontDecoration['yOffset']) ? $fontDecoration['yOffset'] : 0) + $fontSize * 0.35;
                if (!isset($styleArray['font-size'])) {
                    $fontSize *= 0.83333;
                }
                return $this->parseChilds($node, $fontFamily, $fontSize, $color, $fontStyle, $lineHeight, $fontDecoration);

            case 'sub':
                $fontDecoration['yOffset'] = (isset($fontDecoration['yOffset']) ? $fontDecoration['yOffset'] : 0) - $fontSize * 0.2;
                if (!isset($styleArray['font-size'])) {
                    $fontSize *= 0.83333;
                }
                return $this->parseChilds($node, $fontFamily, $fontSize, $color, $fontStyle, $lineHeight, $fontDecoration);

            case 'br':
                return [
                    'type' => 'break',
                    'font' => $this->loadFont($fontFamily, $fontStyle),
                    'fontSize' => $fontSize,
                    'lineHeight' => $lineHeight
                ];
        }

        return $this->parseChilds($node, $fontFamily, $fontSize, $color, $fontStyle, $lineHeight, $fontDecoration);
    }

    protected function parseTextBlocks(array $items)
    {
        $blocks = [];
        $currentBlock = [];

        foreach ($items as $item) {
            if ($item['type'] === 'text') {
                $currentBlock[] = $item;
            } elseif ($item['type'] === 'break') {
                $currentBlock[] = $item;
                $blocks[] = $currentBlock;
                $currentBlock = [];
            }
        }

        if (count($currentBlock) > 0) {
            $blocks[] = $currentBlock;
        }

        $result = [];
        foreach ($blocks as $block) {
            $text = '';
            $offsetMap = [];
            $offset = 0;
            $lastElement = null;
            foreach ($block as $key => $element) {
                if ($element['type'] === 'text') {
                    $text .= $element['text'];

                    $length = strlen($element['text']);
                    $endOffset = $offset + $length;
                    $offsetMap[] = [
                        'start' => $offset,
                        'end' => $endOffset,
                        'elementKey' => $key
                    ];
                    $offset = $endOffset;
                }

                if ($element['type'] === 'break') {
                    $lastElement = $element;
                }
            }

            $lineItt = IntlBreakIterator::createLineInstance($this->locale);
            $lineItt->setText($text);
            $firstPart = true;
            foreach ($lineItt->getPartsIterator(IntlPartsIterator::KEY_LEFT) as $wordStart => $chars) {
                // workaround for an old php bug https://github.com/php/php-src/issues/7734
                if (version_compare(phpversion(), '8.1.5', '<') && $wordStart > 0) {
                    $wordStart--;
                }

                $followingSpaces = substr($chars, strlen(rtrim($chars)));
                $wordEnd = $wordStart + strlen($chars) - strlen($followingSpaces);

                // this part ignores all spaces at the start of a text
                if ($firstPart && $wordStart === $wordEnd) {
                    continue;
                }
                $firstPart = false;

                $spacesStart = $wordEnd;
                // trim all spaces down to a single space
                $spacesEnd = min($spacesStart + strlen($followingSpaces), $wordEnd + 1);
                //$spacesEnd = $spacesStart + strlen($followingSpaces);

                $wordFragments = [];
                $spaceFragments = [];
                foreach ($offsetMap as $offsetEntry) {
                    $findFragment = function ($start, $end) use ($offsetEntry, $block, $text) {
                        if (
                            ($start >= $offsetEntry['start'] && $start < $offsetEntry['end'])
                            || ($end > $offsetEntry['start'] && $end <= $offsetEntry['end'])
                            || ($start < $offsetEntry['start'] && $end > $offsetEntry['end'])
                        ) {
                            $element = $block[$offsetEntry['elementKey']];
                            if ($start < $offsetEntry['start']) {
                                $charsStart = $offsetEntry['start'];
                            } else {
                                $charsStart = $start;
                            }
                            if ($end > $offsetEntry['end']) {
                                $charsEnd = $offsetEntry['end'];
                            } else {
                                $charsEnd = $end;
                            }

                            $chars = substr($text, $charsStart, $charsEnd - $charsStart);
                            // replace nbsp with whitespace
                            $chars = str_replace("\xC2\xA0", ' ', $chars);

                            return [
                                'text' => mb_convert_encoding($chars, 'UTF-16BE', 'UTF-8'),
                                'font' => $element['font'],
                                'fontSize' => $element['fontSize'],
                                'color' => $element['color'],
                                'lineHeight' => $element['lineHeight'],
                                'fontDecoration' => $element['fontDecoration']
                            ];
                        }

                        return null;
                    };

                    $fragment = $findFragment($wordStart, $wordEnd);
                    if ($fragment !== null) {
                        $wordFragments[] = $fragment;
                    }
                    $fragment = $findFragment($spacesStart, $spacesEnd);
                    if ($fragment !== null) {
                        $spaceFragments[] = $fragment;
                    }
                }

                $result[] = [
                    'type' => 'word',
                    'fragments' => $wordFragments,
                    'followingSpacesFragments' => $spaceFragments
                ];
            }

            if ($lastElement !== null) {
                $result[] = $lastElement;
            }
        }

        return $result;
    }

    /**
     * @param array $items
     * @return array
     */
    protected function calculateItemWidths(array $items)
    {
        foreach ($items as $key => $item) {
            if ($item['type'] !== 'word') {
                continue;
            }

            $width = 0;
            $followingSpacesWidth = 0;
            foreach ($item['fragments'] as $fragmentKey => $fragment) {
                /**
                 * @var SetaPDF_Core_Font $font
                 */
                $font = $fragment['font'];
                $w = $font->getGlyphsWidth($fragment['text']) / 1000 * $fragment['fontSize'];
                $items[$key]['fragments'][$fragmentKey]['width'] = $w;
                $width += $w;
            }

            foreach ($item['followingSpacesFragments'] as $fragmentKey => $fragment) {
                /**
                 * @var SetaPDF_Core_Font $font
                 */
                $font = $fragment['font'];
                $w = $font->getGlyphsWidth($fragment['text']) / 1000 * $fragment['fontSize'];
                $items[$key]['followingSpacesFragments'][$fragmentKey]['width'] = $w;
                $followingSpacesWidth += $w;
            }

            $items[$key]['width'] = $width;
            $items[$key]['followingSpacesWidth'] = $followingSpacesWidth;
        }
        return $items;
    }

    /**
     * @return array|null
     * @throws SetaPDF_Core_Exception
     */
    protected function calculateLines()
    {
        if ($this->calculatedLines !== null) {
            return $this->calculatedLines;
        }

        $textElements = $this->parseText();
        $textElements = $this->parseTextBlocks($textElements);
        $textElementsWithWidths = $this->calculateItemWidths($textElements);

        $lines = [];
        $currentLine = [];
        $widthBetweenPreviousItem = 0;
        $currentLineWidth = 0;
        $currentLineWidthWithoutSpaces = 0;

        $startNewLine = function ($lastLine = false) use (&$currentLine, &$currentLineWidth, &$currentLineWidthWithoutSpaces, &$lines, &$widthBetweenPreviousItem) {
            $lines[] = [
                'content' => $currentLine,
                'width' => $currentLineWidth,
                'widthWithoutSpaces' => $currentLineWidthWithoutSpaces,
                'lastLine' => $lastLine
            ];
            $currentLine = [];
            $widthBetweenPreviousItem = 0;
            $currentLineWidth = 0;
            $currentLineWidthWithoutSpaces = 0;
        };

        $addWordToCurrentLine = function ($item) use(&$currentLine, &$currentLineWidth, &$currentLineWidthWithoutSpaces, &$widthBetweenPreviousItem) {
            $currentLine[] = $item;
            $currentLineWidth += $widthBetweenPreviousItem + $item['width'];
            $currentLineWidthWithoutSpaces += $item['width'];
            $widthBetweenPreviousItem = $item['followingSpacesWidth'];
        };

        while (($item = array_shift($textElementsWithWidths)) !== null) {
            if ($item['type'] === 'break') {
                if (count($currentLine) === 0) {
                    // we need this because the empty line requires a line height
                    $addWordToCurrentLine([
                        'type' => 'word',
                        'fragments' => [
                            [
                                'text' => '',
                                'font' => $item['font'],
                                'fontSize' => $item['fontSize'],
                                'lineHeight' => $item['lineHeight'],
                            ]
                        ],
                        'followingSpacesFragments' => [],
                        'width' => 0,
                        'followingSpacesWidth' => 0,
                    ]);
                }
                $startNewLine(true);
                continue;
            }

            if ($item['type'] !== 'word') {
                continue;
            }

            if ($this->width === null || $currentLineWidth + $widthBetweenPreviousItem + $item['width'] <= $this->width) {
                $addWordToCurrentLine($item);
                continue;
            }
            // not enough space in current line

            // start a new line if a line was already started
            if (count($currentLine) > 0) {
                $startNewLine();
            }

            // if the word is longer than the complete available space we break inside the word
            if ($item['width'] > $this->width) {
                $currentWord = [];
                $currentWordWidth = 0;
                foreach ($item['fragments'] as $fragment) {
                    if ($currentLineWidth + $currentWordWidth + $fragment['width'] <= $this->width) {
                        $currentWord[] = $fragment;
                        $currentWordWidth += $fragment['width'];
                        continue;
                    }
                    /**
                     * @var SetaPDF_Core_Font $font
                     */
                    $font = $fragment['font'];

                    $currentChars = '';
                    $currentCharsWidth = 0;
                    $charItt = IntlBreakIterator::createCharacterInstance($this->locale);
                    $charItt->setText(mb_convert_encoding($fragment['text'], 'UTF-8', 'UTF-16BE'));
                    foreach ($charItt->getPartsIterator() as $char) {
                        $char = mb_convert_encoding($char, 'UTF-16BE', 'UTF-8');
                        $w = $font->getGlyphsWidth($char) / 1000 * $fragment['fontSize'];
                        if ($currentLineWidth + $currentWordWidth + $currentCharsWidth + $w <= $this->width) {
                            $currentChars .= $char;
                            $currentCharsWidth += $w;
                            continue;
                        }

                        if ($currentChars !== '') {
                            $currentWord[] = [
                                'text' => $currentChars,
                                'font' => $fragment['font'],
                                'fontSize' => $fragment['fontSize'],
                                'color' => $fragment['color'],
                                'lineHeight' => $fragment['lineHeight'],
                                'fontDecoration' => $fragment['fontDecoration'],
                                'width' => $currentCharsWidth
                            ];
                            $currentWordWidth += $currentCharsWidth;
                        }
                        $currentChars = $char;
                        $currentCharsWidth = $w;

                        $addWordToCurrentLine([
                            'type' => 'word',
                            'fragments' => $currentWord,
                            'followingSpacesFragments' => [],
                            'width' => $currentWordWidth,
                            'followingSpacesWidth' => 0,
                        ]);
                        $startNewLine();
                        $currentWord = [];
                        $currentWordWidth = 0;
                    }

                    if ($currentChars !== '') {
                        $currentWord[] = [
                            'text' => $currentChars,
                            'font' => $fragment['font'],
                            'fontSize' => $fragment['fontSize'],
                            'color' => $fragment['color'],
                            'lineHeight' => $fragment['lineHeight'],
                            'fontDecoration' => $fragment['fontDecoration'],
                            'width' => $currentCharsWidth
                        ];
                        $currentWordWidth += $currentCharsWidth;
                    }
                }

                if (count($currentWord) > 0) {
                    $addWordToCurrentLine([
                        'type' => 'word',
                        'fragments' => $currentWord,
                        'followingSpacesFragments' => $item['followingSpacesFragments'],
                        'width' => $currentWordWidth,
                        'followingSpacesWidth' => $item['followingSpacesWidth'],
                    ]);
                }

                continue;
            }

            // otherwise we force at least the first text item into the line
            $addWordToCurrentLine($item);
        }

        if (count($currentLine) > 0) {
            $startNewLine(true);
        }

        if ($this->width === null) {
            // if no width is given we calculate it dynamicly by using the longest line width
            if (count($lines) === 0) {
                $this->dynamicWidth = 0;
            } else {
                $this->dynamicWidth = max(0, ...array_column($lines, 'width'));
            }
        }

        $lines = $this->fixWordsWithNbsp($lines);
        $lines = $this->calculateLineHeights($lines);

        $this->calculatedLines = $lines;
        return $lines;
    }

    /**
     * Split up words which contain spaces created by &nbsp;.
     * Primarily needed for proper justify alignment as &nbsp; spaces are also stretched.
     *
     * @param array $lines
     * @return array
     */
    protected function fixWordsWithNbsp($lines) {
        foreach ($lines as $key => $line) {
            $items = [];
            foreach ($line['content'] as $textItem) {
                $hasSpace = false;
                foreach ($textItem['fragments'] as $fragment) {
                    if (mb_strpos($fragment['text'], "\x00\x20", 0, 'UTF-16BE') !== false) {
                        $hasSpace = true;
                    }
                }

                if (!$hasSpace) {
                    $items[] = $textItem;
                    continue;
                }

                $fragmentsOfCurrentItem = [];
                foreach ($textItem['fragments'] as $fragment) {
                    if (mb_strpos($fragment['text'], "\x00\x20", 0, 'UTF-16BE') === false) {
                        $fragmentsOfCurrentItem[] = $fragment;
                        continue;
                    }

                    /**
                     * @var SetaPDF_Core_Font $font
                     */
                    $font = $fragment['font'];

                    $text = mb_convert_encoding($fragment['text'], 'UTF-8', 'UTF-16BE');
                    $parts = preg_split('~(\s+)~u', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                    // the part count will always be uneven - pairs of two will be captured as a single word
                    while (count($parts) > 1) {
                        $word = mb_convert_encoding(array_shift($parts), 'UTF-16BE', 'UTF-8');
                        $wordWidth = $font->getGlyphsWidth($word) / 1000 * $fragment['fontSize'];
                        $spaces = mb_convert_encoding(array_shift($parts), 'UTF-16BE', 'UTF-8');
                        $spacesWidth = $font->getGlyphsWidth($spaces) / 1000 * $fragment['fontSize'];

                        $fragmentsWidth = array_sum(array_column($fragmentsOfCurrentItem, 'width'));

                        $items[] = [
                            'type' => 'word',
                            'fragments' => array_merge($fragmentsOfCurrentItem, [[
                                'text' => $word,
                                'font' => $fragment['font'],
                                'fontSize' => $fragment['fontSize'],
                                'color' => $fragment['color'],
                                'lineHeight' => $fragment['lineHeight'],
                                'fontDecoration' => $fragment['fontDecoration'],
                                'width' => $wordWidth
                            ]]),
                            'followingSpacesFragments' => [
                                [
                                    'text' => $spaces,
                                    'font' => $fragment['font'],
                                    'fontSize' => $fragment['fontSize'],
                                    'color' => $fragment['color'],
                                    'lineHeight' => $fragment['lineHeight'],
                                    'fontDecoration' => $fragment['fontDecoration'],
                                    'width' => $spacesWidth
                                ]
                            ],
                            'width' => $fragmentsWidth + $wordWidth,
                            'followingSpacesWidth' => $spacesWidth,
                        ];
                        $lines[$key]['widthWithoutSpaces'] -= $spacesWidth;
                        $fragmentsOfCurrentItem = [];
                    }

                    if ($parts[0] === '') {
                        continue;
                    }

                    $word = mb_convert_encoding(array_shift($parts), 'UTF-16BE', 'UTF-8');
                    $wordWidth = $font->getGlyphsWidth($word) / 1000 * $fragment['fontSize'];

                    $items[] = [
                        'type' => 'word',
                        'fragments' => [
                            [
                                'text' => $word,
                                'font' => $fragment['font'],
                                'fontSize' => $fragment['fontSize'],
                                'color' => $fragment['color'],
                                'fontDecoration' => $fragment['fontDecoration'],
                                'lineHeight' => $fragment['lineHeight'],
                                'width' => $wordWidth
                            ]
                        ],
                        'followingSpacesFragments' => [],
                        'width' => $wordWidth,
                        'followingSpacesWidth' => 0,
                    ];
                }

                $items[] = [
                    'type' => 'word',
                    'fragments' => $fragmentsOfCurrentItem,
                    'followingSpacesFragments' => $textItem['followingSpacesFragments'],
                    'width' => array_sum(array_column($fragmentsOfCurrentItem, 'width')),
                    'followingSpacesWidth' => $textItem['followingSpacesWidth'],
                ];
            }
            $lines[$key]['content'] = $items;
        }

        return $lines;
    }

    protected function calculateLineHeights(array $lines)
    {
        // calculate line heights
        foreach ($lines as $key => $line) {
            $maxAscender = 0;
            $maxDescender = 0;
            foreach ($line['content'] as $textItem) {
                foreach ($textItem['fragments'] as $fragment) {
                    if ($fragment['lineHeight'] === 0) {
                        continue;
                    }

                    /**
                     * @var SetaPDF_Core_Font $font
                     */
                    $font = $fragment['font'];
                    $fontSize = $fragment['fontSize'];


                    $ascender = (float) ($font->getAscent()  / 1000 * $fontSize);
                    $descender = (float) -($font->getDescent() / 1000 * $fontSize);
                    $p = (($ascender + $descender) * $fragment['lineHeight'] - ($ascender + $descender));
                    $ascender += $p / 2;
                    $descender += $p / 2;

                    if (isset($fragment['fontDecoration']['yOffset'])) {
                        $ascender += $fragment['fontDecoration']['yOffset'];
                        $descender -= $fragment['fontDecoration']['yOffset'];
                    }

                    if ($ascender > $maxAscender) {
                        $maxAscender = $ascender;
                    }

                    if ($descender > $maxDescender) {
                        $maxDescender = $descender;
                    }
                }
            }

            $lines[$key]['maxAscender'] = $maxAscender;
            $lines[$key]['maxDescender'] = $maxDescender;
        }
        return $lines;
    }

    /**
     * @param SetaPDF_Core_Canvas $canvas
     * @param float|int $x
     * @param float|int $y
     * @return void
     * @throws SetaPDF_Core_Exception
     * @throws SetaPDF_Core_Font_Exception
     * @throws SetaPDF_Core_Type_Exception
     * @throws SetaPDF_Core_Type_IndirectReference_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    protected function drawText(SetaPDF_Core_Canvas $canvas, $x, $y)
    {
        $lines = $this->calculateLines();
        $underlines = [];

        $text = $canvas->text();
        $text->begin()->moveToNextLine($x, $y);

        $width = $this->getTextWidth();
        $currentY = $y;
        $prevMaxDescender = 0;
        $prevLineLeft = 0;
        $spaceSize = 0;
        $xCursor = 0;

        $previousFont = null;
        $previousFontSize = null;
        $previousColor = null;
        foreach ($lines as $line) {
            $lineContent = $line['content'];
            $currentY -= $line['maxAscender'] + $prevMaxDescender;

            $lineLeft = 0;
            if ($this->align === SetaPDF_Core_Text::ALIGN_CENTER) {
                $lineLeft = ($width - $line['width']) / 2;
            } elseif ($this->align === SetaPDF_Core_Text::ALIGN_RIGHT) {
                $lineLeft = $width - $line['width'];
            } elseif ($this->align === SetaPDF_Core_Text::ALIGN_JUSTIFY
                || $this->align === SetaPDF_Core_Text::ALIGN_JUSTIFY_ALL
            ) {
                $spaces = array_sum(array_map(
                    static function ($word) {
                        return array_sum(array_map(static function ($fragment) {
                            return mb_strlen($fragment['text'], 'UTF-16BE');
                        }, $word['followingSpacesFragments']));
                    },
                    array_slice($lineContent, 0, -1)
                ));

                // prevent division by zero
                if ($spaces === 0) {
                    $spaceSize = 0;
                } else {
                    $spaceSize = ($width - $line['widthWithoutSpaces']) / $spaces;
                }
            }

            $text->moveToNextLine($lineLeft - $prevLineLeft - $xCursor, -$line['maxAscender'] - $prevMaxDescender);
            $prevLineLeft = $lineLeft;
            $lineX = 0;
            $xCursor = 0;

            $prevMaxDescender = $line['maxDescender'];

            $lastKey = key(array_slice($lineContent, -1, 1, true));
            foreach ($lineContent as $key => $textItem) {
                $fragments = $textItem['fragments'];
                $justifyLine = (
                    ($this->align === SetaPDF_Core_Text::ALIGN_JUSTIFY && !$line['lastLine'])
                    || $this->align === SetaPDF_Core_Text::ALIGN_JUSTIFY_ALL
                );
                if ($key !== $lastKey && !$justifyLine) {
                    $fragments = array_merge($fragments, $textItem['followingSpacesFragments']);
                }

                foreach ($fragments as $fragment) {
                    if ($fragment['text'] === '') {
                        continue;
                    }

                    /**
                     * @var SetaPDF_Core_Font $font
                     */
                    $font = $fragment['font'];
                    $fontSize = $fragment['fontSize'];
                    if ($font !== $previousFont || $previousFontSize !== $fontSize) {
                        $text->setFont($font, $fontSize);
                        $previousFont = $font;
                        $previousFontSize = $fontSize;
                    }

                    $color = $fragment['color'];
                    if ($previousColor === null || $previousColor->toPhp() !== $color->toPhp()) {
                        $text->setNonStrokingColor($color);
                        $previousColor = $color;
                    }

                    $yOffset = isset($fragment['fontDecoration']['yOffset']) ? $fragment['fontDecoration']['yOffset'] : 0;
                    if (SetaPDF_Core::isNotZero($yOffset)) {
                        $text->moveToNextLine($lineX - $xCursor, $yOffset);
                        $charCodes = $font->getCharCodes($fragment['text']);
                        $text->showText($charCodes);
                        $text->moveToNextLine($fragment['width'], -$yOffset);
                        $xCursor = $lineX + $fragment['width'];
                    } else {
                        $charCodes = $font->getCharCodes($fragment['text']);
                        $text->showText($charCodes);
                    }


                    if (isset($fragment['fontDecoration']['underline']) && $yOffset >= 0) {
                        $underlines[] = [
                            'x' => $x + $lineX + $lineLeft,
                            'y' => $currentY + ($font->getUnderlinePosition() / 1000 * $fragment['fontDecoration']['underline']),
                            'width' => $fragment['width'],
                            'height' => -($font->getUnderlineThickness() / 1000 * $fragment['fontDecoration']['underline']),
                            'color' => $color
                        ];
                    }
                    $lineX += $fragment['width'];
                }

                if ($key !== $lastKey && $justifyLine && $textItem['followingSpacesWidth'] > 0) {
                    foreach ($textItem['followingSpacesFragments'] as $fragment) {
                        if ($fragment['text'] === '') {
                            continue;
                        }

                        /**
                         * @var SetaPDF_Core_Font $font
                         */
                        $font = $fragment['font'];
                        $fontSize = $fragment['fontSize'];
                        if ($font !== $previousFont || $previousFontSize !== $fontSize) {
                            $text->setFont($font, $fontSize);
                            $previousFont = $font;
                            $previousFontSize = $fontSize;
                        }

                        $spaceCountOfFragment = mb_strlen($fragment['text'], 'UTF-16BE');
                        $text->setCharacterSpacing($spaceSize - ($fragment['width'] / $spaceCountOfFragment));
                        $charCodes = $font->getCharCodes($fragment['text']);
                        $text->showText($charCodes);

                        if (isset($fragment['fontDecoration']['underline'])) {
                            $underlines[] = [
                                'x' => $x + $lineX + $lineLeft,
                                'y' => $currentY + ($font->getUnderlinePosition() / 1000 * $fragment['fontDecoration']['underline']),
                                'width' => $spaceSize * $spaceCountOfFragment,
                                'height' => -($font->getUnderlineThickness() / 1000 * $fragment['fontDecoration']['underline']),
                                'color' => $fragment['color']
                            ];
                        }
                        $lineX += $spaceSize * $spaceCountOfFragment;
                    }

                    $text->setCharacterSpacing(0);
                }
            }
        }
        $text->end();

        if (count($underlines) > 0) {
            foreach ($underlines as $underline) {
                if ($previousColor === null || $previousColor->toPhp() !== $underline['color']->toPhp()) {
                    $canvas->setNonStrokingColor($underline['color']);
                    $previousColor = $underline['color'];
                }

                $canvas->draw()->rect(
                    $underline['x'],
                    $underline['y'],
                    $underline['width'],
                    $underline['height'],
                    SetaPDF_Core_Canvas_Draw::STYLE_FILL
                );
            }
        }
    }

    /**
     * Draws the border and background onto the canvas.
     *
     * @param SetaPDF_Core_Canvas $canvas
     * @param int|float $x The lower left x-value of the text block
     * @param int|float $y The lower left y-value of the text block
     * @throws SetaPDF_Core_Exception
     */
    protected function drawBorderAndBackground(SetaPDF_Core_Canvas $canvas, $x, $y)
    {
        $borderWidth = $this->getBorderWidth();
        $drawStyle = 0;
        if ($borderWidth > 0) {
            $canvas->path()->setLineWidth($borderWidth);
            $canvas->setStrokingColor($this->getBorderColor());
            $drawStyle |= SetaPDF_Core_Canvas_Draw::STYLE_DRAW;
        }

        $backgroundColor = $this->getBackgroundColor();
        if ($backgroundColor !== null) {
            $canvas->setNonStrokingColor($backgroundColor);
            $drawStyle |= SetaPDF_Core_Canvas_Draw::STYLE_FILL;
        }

        if ($drawStyle > 0) {
            $canvas->draw()->rect(
                $x + $borderWidth / 2,
                $y + $borderWidth / 2,
                $this->getWidth() - $borderWidth,
                $this->getHeight() - $borderWidth,
                $drawStyle
            );
        }
    }

    /**
     * Draws the text block onto a canvas.
     *
     * @param SetaPDF_Core_Canvas $canvas
     * @param int|float $x The lower left x-value of the text block
     * @param int|float $y The lower left y-value of the text block
     * @throws SetaPDF_Core_Exception
     * @throws SetaPDF_Core_Font_Exception
     * @throws SetaPDF_Core_Type_Exception
     * @throws SetaPDF_Core_Type_IndirectReference_Exception
     * @throws SetaPDF_Exception_NotImplemented
     */
    public function draw(SetaPDF_Core_Canvas $canvas, $x, $y)
    {
        $canvas->saveGraphicState();

        $this->drawBorderAndBackground($canvas, $x, $y);

        $borderWidth = $this->getBorderWidth();
        $x += $this->getPaddingLeft() + $borderWidth;
        $y += $this->getHeight() - $this->paddingTop - $borderWidth;

        $this->drawText($canvas, $x, $y);

        $canvas->restoreGraphicState();
    }
}
