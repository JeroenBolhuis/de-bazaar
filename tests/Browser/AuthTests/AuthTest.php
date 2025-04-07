<?php

namespace Tests\Browser;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use App\Models\User;
class AuthTest extends DuskTestCase
{
    #[Test]
    public function user_can_register_without_advertisement_access()
    {
        $email = 'testuser@example.com';

        $this->browse(function (Browser $browser) use ($email) {
// Ensure user is logged out first
            $browser->visit('/logout') // This triggers Laravel's logout route
            ->pause(500)
                ->visit(route('register'))
                ->screenshot("register-page")
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

    #[Test]
    public function user_can_login_without_advertisement_access()
    {
        $email = 'user@debazaar.nl';
        $password = 'Jagoed123!';

        // Ensure password is correct for seeded user
        $user = \App\Models\User::where('email', $email)->first();
        if (!$user) {
            throw new \Exception("User with email {$email} not found. Did you seed the database?");
        }
        $user->update(['password' => bcrypt($password)]);

        $this->browse(function (Browser $browser) use ($email, $password) {
            // Force logout by clearing cookies and visiting logout route
            $browser->driver->manage()->deleteAllCookies();
            $browser->visit('/logout')
                ->pause(300);

            // Perform login
            $browser->visit(route('login'))
                ->screenshot('login-form-loaded')
                ->type('email', $email)
                ->type('password', $password)
                ->press('LOG IN')
                ->pause(1000)
                ->screenshot('jagoed-in-dashboard')
                ->assertPathIs('/dashboard');
        });
    }
    #[Test]
    public function user_can_logout_using_dropdown()
    {
        $user = $this->loginAndReturnSeededUser('user@debazaar.nl');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->click('@user-dropdown-trigger')
                ->pause(300)
                ->click('@logout-link')
                ->pause(500)
                ->assertPathIs('/')
                ->assertGuest();
        });
    }
    #[Test]
    private function loginAndReturnSeededUser(string $email, string $password = 'Jagoed123!'): User
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw new \Exception("User with email {$email} not found. Did you seed the database?");
        }

        $user->update(['password' => bcrypt($password)]);

        return $user;
    }
}
