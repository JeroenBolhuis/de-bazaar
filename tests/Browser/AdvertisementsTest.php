<?php

namespace Tests\Browser;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Advertisement;


class AdvertisementsTest extends DuskTestCase
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

        // Set screenshot directory
        \Laravel\Dusk\Browser::$storeScreenshotsAt = base_path('tests/Browser/screenshots');
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

    #[Test]
    public function user_can_create_listing_advertisement()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        // Clean up: delete existing ads for this user
        Advertisement::where('user_id', $user->id)->delete();

        // Verify cleanup
        $this->assertEquals(0, Advertisement::where('user_id', $user->id)->count(), 'Cleanup failed');

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

    #[Test]
    public function user_can_filter_advertisements_by_type_listing()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->check('types[]', 'rental')
                ->press('Filters toepassen')
                ->screenshot('filter-by-listing')
                ->waitForText('Rental')
                ->assertSee('Rental')
                ->assertDontSee('Listing')
                ->assertDontSee('Auction C');
        });
    }

    #[Test]
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

    #[Test]
    public function user_can_unfavorite_an_advertisement()
    {
        $user = User::factory()->create();

        $advertisement = Advertisement::factory()->create([
            'title' => 'Unfavoritable Ad',
            'type' => 'listing',
            'is_active' => true,
            'user_id' => $user->id,
        ]);

        // Simulate that the user has already favorited this ad
        $user->favorites()->attach($advertisement->id);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->waitForText('Unfavoritable Ad')
                ->assertSee('Unfavoritable Ad')
                ->screenshot("unfavorable-tekst?")
                ->click("@favorite-button-{$advertisement->id}") // Click again to unfavorite
                ->pause(500)
                ->screenshot('after-unfavoriting');
        });

        // Now assert it's gone from the database
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'advertisement_id' => $advertisement->id,
        ]);
    }



    #[Test]
    public function user_can_favorite_an_advertisement()
    {
        $user = User::factory()->create();

        $advertisement = Advertisement::factory()->create([
            'title' => 'Favoritable Ad',
            'type' => 'listing',
            'is_active' => true,
            'user_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->waitForText('Favoritable Ad')
                ->click("@favorite-button-{$advertisement->id}")
                ->pause(500)
                ->screenshot('after-favoriting');
        });

        // Assert in the database that the favorite was stored
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'advertisement_id' => $advertisement->id,
        ]);
    }

    #[Test]
    public function user_can_edit_their_advertisement()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');
        $advertisement = Advertisement::factory()->create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'description' => 'Original Description',
            'price' => 100,
            'type' => 'listing',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.show', $advertisement))
                ->clickLink('Edit Advertisement')
                ->assertPathIs("/advertisements/{$advertisement->id}/edit")
                ->type('title', 'Updated Title')
                ->type('description', 'Updated Description')
                ->type('price', '200')
                ->press('UPDATE LISTING')
                ->assertPathIs("/advertisements/{$advertisement->id}")
                ->assertSee('Updated Title')
                ->assertSee('Updated Description')
                ->assertSee('€200');
        });
    }

    #[Test]
    public function user_can_delete_their_advertisement()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');
        $advertisement = Advertisement::factory()->create([
            'user_id' => $user->id,
            'title' => 'To Be Deleted',
            'type' => 'listing',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.show', $advertisement))
                ->press('Delete Advertisement')
                ->acceptDialog()
                ->assertPathIs('/advertisements')
                ->assertDontSee('To Be Deleted');
        });

        $this->assertDatabaseMissing('advertisements', [
            'id' => $advertisement->id
        ]);
    }

    #[Test]
    public function user_can_view_advertisement_details()
    {
        $user = User::factory()->create();
        $advertisement = Advertisement::factory()->create([
            'title' => 'Detailed Ad',
            'description' => 'This is a detailed description',
            'price' => 150,
            'type' => 'listing',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.index'))
                ->clickLink('Detailed Ad')
                ->assertPathIs("/advertisements/{$advertisement->id}")
                ->assertSee('Detailed Ad')
                ->assertSee('This is a detailed description')
                ->assertSee('€150')
                ->assertSee('Listing');
        });
    }

    #[Test]
    public function user_can_toggle_advertisement_status()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');
        $advertisement = Advertisement::factory()->create([
            'user_id' => $user->id,
            'title' => 'Toggle Status Ad',
            'type' => 'listing',
            'is_active' => true
        ]);

        $this->browse(function (Browser $browser) use ($user, $advertisement) {
            $browser->loginAs($user)
                ->visit(route('advertisements.show', $advertisement))
                ->clickLink('Deactivate Advertisement')
                ->assertPathIs("/advertisements/{$advertisement->id}")
                ->assertSee('Advertisement is now inactive')
                ->clickLink('Activate Advertisement')
                ->assertPathIs("/advertisements/{$advertisement->id}")
                ->assertSee('Advertisement is now active');
        });
    }

    #[Test]
    public function price_validation_works_correctly()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit(route('advertisements.create'))
                ->select('type', 'listing')
                ->type('title', 'Invalid Price Ad')
                ->type('description', 'Testing price validation')
                ->type('price', '-100')
                ->press('CREATE LISTING')
                ->assertSee('The price must be greater than 0')
                ->type('price', '0')
                ->press('CREATE LISTING')
                ->assertSee('The price must be greater than 0')
                ->type('price', '100')
                ->press('CREATE LISTING')
                ->assertPathIs('/advertisements')
                ->assertSee('Invalid Price Ad');
        });
    }
}
