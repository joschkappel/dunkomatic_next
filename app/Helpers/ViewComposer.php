<?php

namespace App\Helpers;

use App\Menu;
use Illuminate\View\View;

class ViewComposer
{
    /**
     * @var menu
     */
    private $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    public function compose(View $view)
    {
        $view->with('menu', $this->menu);
    }
}
