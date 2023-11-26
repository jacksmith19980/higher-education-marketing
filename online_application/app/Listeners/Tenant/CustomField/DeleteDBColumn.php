<?php

namespace App\Listeners\Tenant\CustomField;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Events\Tenant\CustomField\CustomFieldDeleted;

class DeleteDBColumn
{


    /**
     * Handle the event.
     *
     * @param  CustomFieldCreated  $event
     * @return void
     */
    public function handle(CustomFieldDeleted $event)
    {
        $column = $event->customfield;

        if (Schema::connection('tenant')->hasColumn($column['object'], $column['slug']))
        {
            try {
                Schema::connection('tenant')->table($column['object'], function (Blueprint $table) use ($column ) {
                    $table->dropColumn($column['slug']);
                });
            } catch (\Exception $e) {
                //throw $th;
            }
        }
    }
}
