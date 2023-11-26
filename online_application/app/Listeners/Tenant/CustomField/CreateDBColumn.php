<?php

namespace App\Listeners\Tenant\CustomField;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Events\Tenant\CustomField\CustomFieldCreated;

class CreateDBColumn
{

    const VARCHAR = 255;


    /**
     * Handle the event.
     *
     * @param  CustomFieldCreated  $event
     * @return void
     */
    public function handle(CustomFieldCreated $event)
    {

        $column = $this->getColumnDetails($event->customfield);

        $columns = Schema::connection('tenant')->getColumnListing($column['table']);

        $after = $columns[ array_search('created_at' , $columns) - 1 ];

        if (!Schema::connection('tenant')->hasColumn($column['table'], $column['name']))
        {
            try {
                Schema::connection('tenant')->table($column['table'], function (Blueprint $table) use ($column , $after) {
                    if($column['type'] == 'string') {
                        $col = $table->{$column['type']}($column['name'] , $column['length']);

                    }else{
                        $col = $table->{$column['type']}($column['name']);
                    }
                    if($column['unique']){
                        $col->unique();
                    }
                    if($column['default'] && !$column['unique']){
                        $col->default($column['default']);
                    }
                    if(isset($after)){
                        $col->after($after);
                    }
                    if(!$column['required']){
                        $col->nullable();
                    }
                });

            } catch (\Exception $e) {
                     /* ER_TOO_BIG_ROWSIZE */
                    if($e->getErrorCode() === 1118 ){

                    }
            }
        }

    }

    protected function getColumnDetails($customfield)
    {
        $column = [
            'length'    => 255,
            'name'      => $customfield->slug,
            'table'     => $customfield->object,
            'default'   => ($customfield->properties['default_value']) ? $customfield->properties['default_value'] : false,
            'required'  => (isset($customfield->is_required)) ? true : false,
            'unique'    => (isset($customfield->properties['unique'])) ? $customfield->properties['unique'] : false,
        ];

        switch ($customfield->field_type) {
            case 'text':
            case 'file':
            case 'link':
                $column['type'] = 'string';
                break;

            case 'email':
            case 'phone':
                $column['type'] = 'string';
                $column['length'] = 50;

            case 'date':
                $column['type'] = 'date';
            break;

            case 'dateTime':
                $column['type'] = 'dateTime';
            break;

            case 'list':
            case 'multi-list':
                $column['type'] = 'longText';
            break;

            case 'textarea':
                $column['type'] = 'longText';
            break;

            default:
                $column['type'] = 'string';
                $column['length'] = 50;
            break;
        }

        return $column;
    }
}
