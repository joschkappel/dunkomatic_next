<?php

namespace Tests\Browser\Pages\Club;

use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Page;

class EditClub extends Page
{
    protected $club_id;

    public function __construct($club_id)
    {
        $this->club_id = $club_id;
    }

    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/de/club/'.$this->club_id.'/edit';
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
            '@url' => 'input[di=url]',
        ];
    }

    public function modify_clubno(Browser $browser, $club_name, $club_no)
    {
        $browser->value('@name', $club_name)
                ->value('@club_no', $club_no)
                ->press('Senden');
    }
}
