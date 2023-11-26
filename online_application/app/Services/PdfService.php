<?php

namespace App\Services;

// use DynamicPDF\Api\PdfResource;
// use DynamicPDF\Api\PdfInfo;
// use DynamicPDF\Api\Pdf;
// use CompletingAcroForm as GlobalCompletingAcroForm;
// use DynamicPDF\Api\FormField;
// use DynamicPDF\Api\PdfText;
// use DynamicPDF\Api\PdfXmp;

use Storage;

require_once __DIR__ . '/SetaPDF/Autoload.php';

class PdfService
{

    public function readInfo($filename)
    {
        $filePath = storage_path('app/').$filename;
        $document = \SetaPDF_Core_Document::loadByFilename($filePath);
        $formFiller = new \SetaPDF_FormFiller($document);
        $fields = $formFiller->getFields();

        $formFields = [
            'signatureFields' => [],
            'textFields' => [],
            'choiceFields' => [],
        ];

        foreach ($fields as $field) {
            $fieldType = strtolower(get_class($field));
            switch ($fieldType) {
                case 'setapdf_formfiller_field_signature':
                    $formFields['signatureFields'][] = [
                        'name' => $field->getName(),
                        'signed' => false,
                    ];
                    break;
                case 'setapdf_formfiller_field_text':
                    $formFields['textFields'][] = [
                        'name' => $field->getName(),
                        'value' => '',
                        'defaultValue' => '',
                    ];
                    break;
                case 'setapdf_formfiller_field_combo':
                    $options = $field->getOptions();
                    $options = array_map(function ($item) {
                        return $item["visibleValue"];
                    }, $options);
                    $formFields['choiceFields'][] = [
                        'name' => $field->getName(),
                        'type' => 'comboBox',
                        'value' => count($options)>0 ? $options[0] : '',
                        'defaultValue' => count($options)>0 ? $options[0] : '',
                        'exportValue' => count($options)>0 ? $options[0] : '',
                        'items' => $options,
                        'itemExportValues' => $options,
                    ];
                    break;
                default:
                    break;
            }
        }
        
        $result = ['formFields' => $formFields, 'title' => ''];
        $resultJson = json_decode(json_encode($result));

        return $resultJson;
    }

    public function writeInfo($filename, $data)
    {
        $imgType = 'png';

        $contents = Storage::disk('s3')->get($filename);
        $pdfName = 'tempfile'.rand(0, 100000).'.pdf';
        $filePath = storage_path('app/'.session('tenant').'/documentBuilder/pdfs')."/".$pdfName;
        Storage::disk('local')->put('/'.session('tenant').'/documentBuilder/pdfs/'.$pdfName, $contents);
        $newPdfName = 'tempseta'.rand(0, 100000).'.pdf';
        $newFilePath = storage_path('app/'.session('tenant').'/documentBuilder/pdfs')."/".$newPdfName;
        $writer = new \SetaPDF_Core_Writer_File($newFilePath);
        $document = \SetaPDF_Core_Document::loadByFilename($filePath, $writer);
        $formFiller = new \SetaPDF_FormFiller($document);
        $fields = $formFiller->getFields();
        $fieldNames = $fields->getNames();

        $signatureFieldName = '';
        foreach ($data as $key => $value) {
            if (strpos($key, '|SignatureField') !== false) {
                $signatureFieldName = explode('|', $key)[0];
            }
        }
        
        foreach ($fieldNames as $fieldName) {
            if($fieldName!=$signatureFieldName) {
                $fields[$fieldName]->setValue($data[$fieldName]??'');
            }
        }

        if(isset($fields[$signatureFieldName]) && isset($data[$signatureFieldName.'|SignatureField'])) {
            $annotation = $fields[$signatureFieldName]->getAnnotation();
            $width = $annotation->getWidth();
            $height = $annotation->getHeight();
            $xobject = \SetaPDF_Core_XObject_Form::create($document, array(0, 0, $width, $height));
            $canvas = $xobject->getCanvas();
            $imageName = 'tempimage'.rand(0, 100000).'.png';
            $signaturePath = storage_path('app/'.session('tenant').'/documentBuilder/signatures')."/".$imageName;
            $signatureImgContent = Storage::disk('s3')->get($data[$signatureFieldName.'|SignatureField']);
            Storage::disk('local')->put('/'.session('tenant').'/documentBuilder/signatures/'.$imageName, $signatureImgContent);
            if ($imgType == 'png') {
                $image = \SetaPDF_Core_Image::getByPath($signaturePath)->toXObject($document);
            } else {
                $logoDoc = \SetaPDF_Core_Document::loadByFilename($signaturePath);
                $image = $logoDoc->getCatalog()->getPages()->getPage(1)->toXObject($document, \SetaPDF_Core_PageBoundaries::ART_BOX);
            }
            if ($image->getHeight($width) >= $height) {
				$image->draw(
					$canvas, $width / 2 - $image->getWidth($height) / 2, 0, null, $height
				);
			} else {
				$image->draw(
					$canvas, 0, $height / 2 - $image->getHeight($width) / 2, $width
				);
			}
            $annotation->setAppearance($xobject);
        }

        $fields->flatten();
        $document->save()->finish();

        $content = file_get_contents($newFilePath);

        Storage::disk('local')->delete(session('tenant').'/documentBuilder\pdfs'."/".$pdfName);
        Storage::disk('local')->delete(session('tenant').'/documentBuilder\pdfs'."/".$newPdfName);
        Storage::disk('local')->delete(session('tenant').'/documentBuilder\signatures'."/".$imageName);

        return ['content' => $content, 'name' => 'generated_document.pdf'];
    }

}
