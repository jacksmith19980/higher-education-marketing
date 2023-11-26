<?php

namespace App\Actions;

use App\Actions\Action;

class RedirectToUrl extends Action
{
    /**
     * Run Application Action
     *
     * @return bool
     */
    public function handle()
    {
        return redirect(route('school.agent.home', 1));
    }
}
