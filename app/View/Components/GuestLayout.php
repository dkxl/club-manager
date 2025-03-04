<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Registers 'app-layout' as an extra View Component
 * this means that app.blade.php can live under views/layouts instead of needing
 * to be under views/components/layouts
 */


class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
       return view('layouts.guest');
    }
}
