<?php
namespace App\Events\Tenant\CustomField;

use App\Tenant\Models\CustomField;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CustomFieldDeleted
{
    use Dispatchable, SerializesModels;
    public $customfield;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($customfield)
    {
        $this->customfield = $customfield;
    }
}
?>
