<?php

namespace App\Mail\Tenant;

use App\School;
use App\Tenant\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $parent;
    public $school;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $parent, $school, $password)
    {
        $this->parent = $parent;
        $this->school = School::bySlug($school);
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('front.parent.mail.password-email');
    }
}
