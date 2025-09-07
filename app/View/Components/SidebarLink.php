<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class SidebarLink extends Component
{
    public bool $active;

    /**
     * Create a new component instance.
     */
    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.sidebar-link');
    }
}