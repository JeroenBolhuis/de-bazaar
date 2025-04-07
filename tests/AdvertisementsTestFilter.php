<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

class AdvertisementsTestFilter extends DuskTestCase
{
    #[Test]
    public function user_can_reorder_advertisements()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('advertisements.index'))
                ->assertSee('Je Marktplaats voor Alles') // Dutch text
                ->screenshot('before-switch-to-en')

                // Click the English flag
                ->click('@locale-switch-en')
                ->pause(500)

                ->screenshot('after-switch-to-en')
                ->assertSee('Your Marketplace for Everything'); // English text
        });
    }
}
