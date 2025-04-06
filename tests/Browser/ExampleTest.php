<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testBasicExample(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Je');

        });
    }

    /** @test */
    public function user_can_register_without_advertisement_access()
    {
        Artisan::call('db:seed');

        $email = 'testuser@example.com';

        $this->browse(function (Browser $browser) use ($email) {
            $browser->visit(route('register'))
                ->type('name', 'Test User')
                ->type('email', $email)
                ->type('password', 'Jagoed123!')
                ->type('password_confirmation', 'Jagoed123!')
                ->press('REGISTREREN')
                ->pause(1000)
                ->screenshot('after-registration')
                ->assertPathIs('/dashboard')
                ->visit(route('advertisements.create'));
        });
    }

}
