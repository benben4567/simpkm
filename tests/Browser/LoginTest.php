<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'admin@simpkm.com')
                    ->type('password', '123456')
                    ->press('Login')
                    ->assertSee('Dashboard')
                    ->assertPathIs('/home');
        });
    }

    // public function testPeriode()
    // {
    //   $this->browse(function (Browser $browser) {
    //     $browser->visit('/home')
    //             // ->click('a.nav-link.has-dropdown')
    //             ->clickLink('Data PKM')
    //             ->waitForText('Periode Pembukaan')
    //             ->clickLink('Periode Pembukaan')
    //             ->assertSee('Periode Pembukaan');
    //   });
    // }
}
