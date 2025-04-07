<?php
namespace Tests\Browser;
use Illuminate\Support\Facades\Artisan;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
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
    public function user_can_create_listing_advertisement()
    {
        $user = $this->loginAndReturnSeededUser('seller@debazaar.nl');

        // Clean up: delete existing ads for this user
        Advertisement::where('user_id', $user->id)->delete();

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
