<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class QuotationViewComposer
{
    public function compose(View $view)
    {
        if ($quotation = request()->quotation) {
            $view->with('quotation', $quotation);
        }
    }
}
