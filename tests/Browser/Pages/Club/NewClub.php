<?php

namespace Tests\Browser\Pages\Club;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class NewClub extends Page
{
    protected $region_id;

    public function __construct($region_id)
    {
        $this->region_id = $region_id;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/de/region/'.$this->region_id.'/club/create';
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
            '@club_no' => 'input[id=club_no]',
            '@shortname' => 'input[id=shortname]',
            '@name' => 'input[id=name]',
            '@url' => 'input[id=url]',
        ];
    }

    public function new_club(Browser $browser, $club_name, $club_no, $url)
    {
        $browser->value('@shortname', 'VVVV')
                ->value('@name', $club_name)
                ->value('@club_no', $club_no)
                ->value('@url', $url)
                ->screenshot('new_club_1_1')
                ->waitUntilEnabled('.btn-primary')
                ->screenshot('new_club_1_2')
                ->press('Senden');
    }
}
