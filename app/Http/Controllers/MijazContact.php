<?php

namespace App\Http\Controllers;

use App\Traits\SupportTicketManager;

class MijazContact extends Controller
{

    public function ContactMe()
    {
        $pageTitle     = 'Dashboard';
        return view($this->activeTemplate . 'user.mijaz-contact', compact('pageTitle'));
    }
}
