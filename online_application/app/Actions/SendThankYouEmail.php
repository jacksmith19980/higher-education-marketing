<?php

namespace App\Actions;

use App\Mail\Tenant\ApplicationSubmittedTYEmail;
use App\Tenant\Models\ApplicationAction;
use Auth;
use Mail;

class SendThankYouEmail extends Action
{
    /**
     * Run Application Action
     *
     * @return void
     */
    public function handle()
    {
        $recipients = $this->getRecipients($this->action);

        //check if not email by Integration

        if (! isset($this->action->properties['email_from_mautic'])) {
            Mail::to($recipients)
            ->send(new ApplicationSubmittedTYEmail($this->submission, $this->action->properties['email']));
        } else {
            $this->integration->sendEmail((int) $this->action->properties['mautic_email'], $recipients);
        }
    }

    /**
     * Extract a list of recipients
     *
     * @param ApplicationAction $action
     * @return array
     */
    protected function getRecipients(ApplicationAction $action)
    {
        $recipients = [];
        $user = Auth::guard('student')->user();

        if (isset($action->properties['send_to'])) {
            foreach ($action->properties['send_to'] as $object) {
                switch ($object) {
                    case 'student':
                        $recipients[] = $user->email;
                        break;

                    case 'parent':
                        if ($user->parent != null) {
                            $recipients[] = $user->parent->email;
                        }
                        break;

                    case 'agent':
                        if ($user->agent != null) {
                            $recipients[] = $user->agent->email;
                        }
                        break;
                }
            }
        }

        return $recipients;
    }
}
