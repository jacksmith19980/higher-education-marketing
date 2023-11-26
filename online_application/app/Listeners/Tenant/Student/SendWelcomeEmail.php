<?php

namespace App\Listeners\Tenant\Student;

use App\Facades\Token;
use Illuminate\Mail\Message;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Support\Facades\Mail;
use App\Events\Tenant\Student\StudentRegistred;

class SendWelcomeEmail
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  ParentRegistred  $event
     * @return void
     */
    public function handle(StudentRegistred $event)
    {
        $student = $event->student;
        $request = $event->request;
        $school = $event->school;
        $settings = Setting::byGroup('auth');
        if(!isset($settings['auth']['send_welcome_email']) || $settings['auth']['send_welcome_email'] == 'No'){
            return false;
        }

        Mail::send([], [], function (Message $message) use ($settings , $student , $request , $school) {

            $message
                ->to($student->email)
                ->subject($settings['auth']['welcome_email_subject'])
                ->from($settings['auth']['welcome_email_from_email'] , $settings['auth']['welcome_email_from_name'] )
                ->setBody($this->extractEmailBody($student , $school ,$request , $settings['auth']['welcome_email']), 'text/html');

        });
        return true;
    }

    // To replace the token
    protected function extractEmailBody($student , $school ,$request , $html)
    {
        $map = [
            'FIRST_NAME' => $student->first_name,
            'LAST_NAME'  => $student->last_name,
            'EMAIL'      => $student->email,
            'SCHOOL'     => $school->name,
        ];
        $html = Token::replace($map, $html);
        return $html;
    }
}
