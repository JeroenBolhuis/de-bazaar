<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use PHPUnit\Framework\Attributes\Test;

class LocaleSwitchTest extends DuskTestCase
{
    #[Test]
    public function user_can_switch_from_dutch_to_english()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Je Marktplaats voor Alles') // Dutch text
                ->screenshot('before-switch-to-en')

                // Click the English flag
                ->click('@locale-switch-en')
                ->pause(500)

                ->screenshot('after-switch-to-en')
                ->assertSee('Your Marketplace for Everything'); // English text
        });
    }

    #[Test]
    public function user_can_switch_from_english_to_dutch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                // Set the locale to English first manually (optional)
                ->click('@locale-switch-en')
                ->pause(300)

                ->assertSee('Your Marketplace for Everything')
                ->screenshot('before-switch-to-nl')

                ->click('@locale-switch-nl')
                ->pause(500)

                ->screenshot('after-switch-to-nl')
                ->assertSee('Je Marktplaats voor Alles'); // Dutch again
        });
    }
}
