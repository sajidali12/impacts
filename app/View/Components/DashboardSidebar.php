<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class DashboardSidebar extends Component
{
    public string $role;

    /**
     * Create a new component instance.
     */
    public function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.dashboard-sidebar');
    }
}