<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Contract;
use App\Models\User;
class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contract = Contract::create([
            'title' => [
                'nl' => 'Algemene Voorwaarden',
                'en' => 'Terms and Conditions'
            ],
            'content' => [
                'nl' => "ALGEMENE VOORWAARDEN VOOR GEBRUIK VAN HET PLATFORM\n\nWelkom bij De Bazaar, een online platform waarop particulieren en bedrijven producten kunnen aanbieden, verhuren of veilen.\n\nDoor een account aan te maken op ons platform, gaat u akkoord met de volgende voorwaarden:\n\n1. Gebruik van het Platform\n- U dient correcte en actuele gegevens te verstrekken tijdens registratie.\n- Alleen gebruikers van 18 jaar en ouder mogen zich registreren.\n- Het is niet toegestaan illegale of aanstootgevende content te plaatsen.\n\n2. Advertenties en Verhuur\n- Bij het plaatsen van een advertentie verklaart u eigenaar te zijn van het product of gemachtigd te zijn om deze aan te bieden.\n- Verhuurovereenkomsten zijn bindend. Bij het terugbrengen dient de huurder eventuele schade te melden.\n- De verhuurder is verantwoordelijk voor het controleren van het product op slijtage en het uploaden van een retourfoto.\n\n3. Betalingen en Transacties\n- Alle betalingen verlopen via de betaalprovider. De Bazaar is geen partij in de betalingsovereenkomst tussen gebruikers.\n- Er kunnen servicekosten van toepassing zijn. Deze worden voor afronding van de transactie getoond.\n\n4. Beëindiging van het Account\n- Wij behouden ons het recht voor om accounts te blokkeren bij misbruik, fraude of het overtreden van deze voorwaarden.\n\n5. Aansprakelijkheid\n- De Bazaar is niet verantwoordelijk voor geschillen tussen gebruikers.\n- Wij bieden het platform \"zoals het is\" aan zonder enige garanties.\n\n6. Wijzigingen\n- Wij behouden ons het recht voor deze voorwaarden te wijzigen. Bij ingrijpende wijzigingen brengen wij u hiervan op de hoogte.",
                'en' => "TERMS AND CONDITIONS FOR USE OF THE PLATFORM\n\nWelcome to De Bazaar, an online platform where individuals and companies can list, rent or auction products.\n\nBy creating an account on our platform, you agree to the following terms:\n\n1. Use of the Platform\n- You must provide accurate and up-to-date information during registration.\n- Only users 18 years or older may register.\n- It is not allowed to post illegal or offensive content.\n\n2. Listings and Rentals\n- By placing an ad, you declare that you are the owner of the product or authorized to offer it.\n- Rental agreements are binding. Upon return, the renter must report any damages.\n- The owner is responsible for checking the item for wear and uploading a return photo.\n\n3. Payments and Transactions\n- All payments go through the payment provider. [YourPlatformName] is not a party in the transaction agreement between users.\n- Service fees may apply and will be displayed before the transaction is finalized.\n\n4. Account Termination\n- We reserve the right to block accounts in case of abuse, fraud, or violation of these terms.\n\n5. Liability\n- [YourPlatformName] is not responsible for disputes between users.\n- We provide the platform \"as is\" without any guarantees.\n\n6. Changes\n- We reserve the right to change these terms. In case of major changes, you will be informed."
            ],
            'is_active' => true,
        ]);

        foreach (User::all() as $user) {
            $user->contracts()->attach($contract->id);
        }   
    }
    
}
