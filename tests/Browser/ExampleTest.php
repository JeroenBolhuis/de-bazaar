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

    /** @test */
    public function user_can_login_without_advertisement_access()
    {
        Artisan::call('db:seed');

        $email = 'loginuser' . uniqid() . '@example.com';
        $password = 'Jagoed123!';

        // Create a user directly using Eloquent (outside browser)
        \App\Models\User::factory()->create([
            'name' => 'Login User',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->visit(route('login'))
                ->type('email', $email)
                ->type('password', $password)
                ->press('LOG IN')
                ->pause(1000)
                ->screenshot('after-login')
                ->assertPathIs('/dashboard')
                ->visit(route('advertisements.create'));
        });
    }

    /** @test */
    public function user_can_logout_using_dropdown()
    {
        Artisan::call('db:seed');

        $email = 'logoutuser' . uniqid() . '@example.com';
        $password = 'Jagoed123!';

        \App\Models\User::factory()->create([
            'name' => 'Logout Tester',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->visit(route('login'))
                ->type('email', $email)
                ->type('password', $password)
                ->press('LOG IN')
                ->pause(1000)
                ->assertPathIs('/dashboard')
                // 👇 Click on the user name to open dropdown
                ->click('@user-dropdown-trigger')
                ->pause(300)
                // 👇 Click on the 'Log Out' option inside the dropdown
                ->click('@logout-link')
                ->pause(500)
                // 👇 Confirm we're back on home or login
                ->assertPathIs('/')
                ->assertGuest();
        });
    }

    /** @test */
    public function user_can_add_advertisment()
    {
        Artisan::call('db:seed');

        $email = 'logoutuser' . uniqid() . '@example.com';
        $password = 'Jagoed123!';

        \App\Models\User::factory()->create([
            'name' => 'Logout Tester',
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->visit(route('login'))
                ->type('email', $email)
                ->type('password', $password)
                ->press('LOG IN')
                ->pause(1000)
                ->assertPathIs('/dashboard')
                // 👇 Click on the user name to open dropdown
                ->click('@user-dropdown-trigger')
                ->pause(300)
                // 👇 Click on the 'Log Out' option inside the dropdown
                ->click('@logout-link')
                ->pause(500)
                // 👇 Confirm we're back on home or login
                ->assertPathIs('/')
                ->assertGuest();
        });
    }
    /** @test */
    public function user_can_navigate_to_create_advertisement()
    {
        // Arrange
        $email = 'creator' . uniqid() . '@example.com';
        $password = 'Jagoed123!';

        $user = \App\Models\User::factory()->create([
            'email' => $email,
            'password' => bcrypt($password),
            'can_sell' => true, // ✅ heel belangrijk!
        ]);

        // Act
        $this->browse(function (Browser $browser) use ($email, $password) {
            $browser->visit(route('login'))
                ->type('email', $email)
                ->type('password', $password)
                ->press('Login') // of 'Inloggen', afhankelijk van je label
                ->assertPathIs('/dashboard')
                ->visit(route('advertisements.index'))

                // Check of de knop zichtbaar is
                ->assertSee('Create Advertisement')

                // Klik op de knop
                ->clickLink('Create Advertisement')

                // Assert dat we op de juiste route zijn
                ->assertPathIs('/advertisements/create');
        });
    }

}
