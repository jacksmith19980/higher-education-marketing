<?php

namespace DynamicPDF\Api;


include_once __DIR__ . '/Endpoint.php';

class DlexLayout extends Endpoint
{
    private $resource;

    /**
     *
     * Initializes a new instance of the DlexLayout class using the DLEX file path present in the cloud environment
     * and the JSON data for the PDF report.
     *
     * @param  string $cloudDlexPath The DLEX file path present in the resource manager.
     * @param  LayoutDataResource $layoutData The LayoutDataResource, json data file used to create the PDF report.
     */
    public function __construct(string $cloudDlexPath, LayoutDataResource $layoutData)
    {
        parent::__construct();
        $this->DlexPath = $cloudDlexPath;
        $this->resource = $layoutData;
    }

    public $_EndpointName = "dlex-layout";

    /**
     *
     * Gets or sets the DLEX file path present in the resource manager.
     *
     */
    public $DlexPath;

    /**
     *
     * Process the DLEX and layout data to create PDF report.
     *
     * @return PdfResponse Returns collection of PdfResponse tasks.
     */
    public function Process(): PdfResponse
    {
        $client = parent::Init();

        $endPointUrl = rtrim($this->BaseUrl, "\0\t\n\x0B\r \\/") . "/v1.0/" . $this->_EndpointName;
        curl_setopt($client, CURLOPT_URL, $endPointUrl);

        $errCode = json_last_error();
        //echo($this->resource->Data);
        /*------------------------------------------------------------------------------------------------------*/
        $body = array();
        $algos = hash_algos();
        $hashAlgo = null;
        foreach (array('sha1', 'md5') as $preferred) {
            if (in_array($preferred, $algos)) {
                $hashAlgo = $preferred;
                break;
            }
        }

        if ($hashAlgo === null) {
            list($hashAlgo) = $algos;
        }

        $boundary = '----------------------------' .
            substr(hash($hashAlgo, 'sample Text ' . microtime()), 0, 12);

        $crlf = "\r\n";

        //foreach ( $this->Resources as $field )
        {
            $body[] = '--' . $boundary;
            $body[] = 'Content-Disposition: form-data; name="' . "LayoutData" . '"; filename="' . $this->resource->LayoutDataResourceName . '"';
            $body[] = 'Content-Type: ' . $this->resource->_MimeType;
            $body[] = '';
            $body[] = $this->resource->Data;

            $body[] = '--' . $boundary;
            $body[] = 'Content-Disposition: form-data; name="' . "DlexPath" . '"; filename=""';
            $body[] = 'Content-Type: application/octet-stream';
            $body[] = '';
            $body[] = $this->DlexPath;
        }
        $body[] = '--' . $boundary . '--';
        $body[] = '';
        $contentType = 'multipart/form-data; boundary=' . $boundary;
        $content = join($crlf, $body);
        $contentLength = strlen($content);

        curl_setopt(
            $client,
            CURLOPT_HTTPHEADER,
            array(
                'Authorization:Bearer ' . $this->ApiKey,
                'Content-Length: ' . $contentLength,
                'Expect: 100-continue',
                'Content-Type: ' . $contentType
            )
        );

        curl_setopt($client, CURLOPT_POSTFIELDS, $content);

        ob_start();
        $result = curl_exec($client);
        $outData = ob_get_contents();
        ob_end_clean();

        $retObject = new PdfResponse();
        $retObject->IsSuccessful = false;
        $retObject->StatusCode = curl_getinfo($client, CURLINFO_RESPONSE_CODE);

        if ($result && strncmp($outData, '%PDF', 4) == 0) {
            $retObject->IsSuccessful = true;
            $retObject->Content = $outData;
        } elseif (trim($outData)[0] == '{') {
            $retObject->ErrorJson = $outData;
            $errObj = json_decode($outData);
            $retObject->ErrorMessage = $errObj->message ?? $errObj->title ?? null;
            $retObject->ErrorId = $errObj->id ?? $errObj->traceId ?? null;
        }

        curl_close($client);

        return $retObject;
    }
}
