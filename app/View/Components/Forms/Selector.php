<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Selector extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public array|Collection $options,
        public string|null $label = '',
        public string|null $value = '',
        public bool $required = false,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.selector');
    }
}
