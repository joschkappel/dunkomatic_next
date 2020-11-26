<?php

namespace Tests\Browser\Pages\Club;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class NewClub extends Page
{

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/de/club/create';
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
            '@region' => 'input[name=region]',
            '@club_no' => 'input[name=club_no]',
            '@shortname' => 'input[name=shortname]',
            '@name' => 'input[name=name]',
            '@url' => 'input[name=url]',
        ];
    }

    public function new_club(Browser $browser, $club_name, $club_no, $url){
      $browser->value('@shortname','VVVV')
              ->value('@region','HBVDA')
              ->value('@name',$club_name)
              ->value('@club_no',$club_no)
              ->value('@url', $url)
              ->press('Senden');
    }
}
