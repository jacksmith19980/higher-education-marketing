<?php
namespace App\Events\Tenant\Date;

use App\Tenant\Models\Date;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class DateCreated
{
    use Dispatchable, SerializesModels;
    public $date;
    public $lessons;
    public $request;
    public $createGroup;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Date $date, $lessons = [] , $request , $createGroup = false)
    {
        $this->date  = $date;
        $this->lessons  = $lessons;
        $this->request  = $request;
        $this->createGroup  = $createGroup;
    }
}
?>
