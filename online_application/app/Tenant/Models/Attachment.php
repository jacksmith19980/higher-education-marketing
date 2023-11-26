<?php

namespace App\Tenant\Models;

use App\User;
use App\Tenant\Traits\Scope;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;
    use  HasCampuses;

    protected $guarded = [];

    public static $modelName = 'attachment';

    public function object()
    {
        return $this->morphTo();
    }

    public function getTypeAttribute(){
        $temp = explode("." , $this->url);

        if(in_array( strtolower($temp[count($temp) - 1 ]) , ['jpg' , 'png' , 'jpeg' , 'svg' , 'bmp'] )){
            return 'image';
        }
        return 'file';

    }




}
