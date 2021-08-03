<?php

namespace Tests\Browser\Pages\League;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class NewLeague extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/de/league/create';
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

    public function create_league( Browser $browser, $code, $name ){
      $browser->type('shortname',$code)
              ->type('name',$name)
              ->select2('.js-selSize','10er Runde')
              ->select2('.js-sel-schedule', '10er Runden')
              ->screenshot('Neue_runde')
              ->press('Senden');
    }
}
