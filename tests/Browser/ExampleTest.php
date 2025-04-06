<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Advertisement;

class ExampleTest extends DuskTestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        if (!static::$seeded) {
            Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--env' => 'dusk.local',
            ]);
            static::$seeded = true;
        }

        // Optionally clear any auth cookies before each test
        \Laravel\Dusk\Browser::$storeScreenshotsAt = base_path('tests/Browser/screenshots');
    }



    /** @test */
    public function user_can_register_without_advertisement_access()
    {
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
    /** @test */
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



    /** @test */
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

    /** @test */
    public function user_can_add_advertisment()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->clickLink('Create Advertisement')
                ->assertPathIs('/advertisements/create');
        });
    }

    /** @test */
    public function user_can_create_listing_advertisement()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->assertSee('Create Advertisement')
                ->clickLink('Create Advertisement')
                ->assertPathIs('/advertisements/create')
                ->select('type', 'listing')
                ->type('title', 'Test Listing Ad')
                ->type('description', 'This is a test advertisement created by a Dusk test.')
                ->type('price', '25')
                ->press('CREATE LISTING')
                ->pause(1000)
                ->assertSee('Back to Advertisements')
                ->screenshot('after-submitting-listing');
        });
    }

    /** @test */
    public function user_can_favorite_an_advertisement()
    {
        $user = User::factory()->create();

        $advertisement = Advertisement::factory()->create([
            'title' => 'Favoritable Ad',
            'type' => 'listing',
            'is_active' => true,
            'user_id' => $user->id, // Required if user_id is not nullable
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->waitForText('Favoritable Ad')
                ->assertSee('Favoritable Ad')
                ->click("@favorite-button-{$advertisement->id}")
                ->pause(500)
                ->screenshot('after-favoriting')
                ->assertSee('Favoritable Ad');
        });
    }

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
