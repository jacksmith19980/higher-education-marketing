<?php
namespace App\Helpers\Plugins;

abstract class EsignatureAbstract
{
    /**
     * Check if the Token is expired
     *
     * @param [int] $expiresIn
     * @return bool
     */
    public function getTokenExpireTimeStamp($expiresIn)
    {
        return time() + (int) $expiresIn;
    }

    public function getTemplateDocuments($templateId){
        //
    }


}

?>
