<?php

namespace Tests\Browser;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Advertisement;


class AdvertisementTests extends DuskTestCase
{
    #[Test]
    public function user_can_filter_advertisements_by_type_listing()
    {
        $user = User::factory()->create();

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

        // Now assert it’s gone from the database
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'advertisement_id' => $advertisement->id,
        ]);
    }
}
