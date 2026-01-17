<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\View\View;

class Admin extends Component
{
    public function __construct()
    {
    }

    public function render(): View
    {
        return view('layouts.admin');
    }
}
