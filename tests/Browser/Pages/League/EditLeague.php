<?php

namespace Tests\Browser\Pages\League;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditLeague extends Page
{

    protected $league_id;

    public function __construct($league_id)
    {
       $this->league_id = $league_id;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/de/league/'.$this->league_id.'/edit';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param  Browser  $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@element' => '#selector',
        ];
    }
}
