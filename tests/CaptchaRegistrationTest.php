<?php

namespace Tests;

use App\Models\Airline;
use App\Models\Airport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class CaptchaRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Create a default user so installer redirect is skipped
        \App\Models\User::factory()->create();
    }

    protected function baseUserData(): array
    {
        $airline = Airline::factory()->create();
        $airport = Airport::factory()->create(['hub' => true]);
        return [
            'name' => 'Captcha Test',
            'email' => 'captcha-test@example.com',
            'airline_id' => $airline->id,
            'home_airport_id' => $airport->id,
            'password' => 'secret',
            'password_confirmation' => 'secret',
            'toc_accepted' => true,
        ];
    }

    public function test_no_captcha_when_disabled(): void
    {
        $this->updateSetting('captcha.enabled', false);
        $this->updateSetting('captcha.site_key', '');
        $this->updateSetting('captcha.secret_key', '');

        $resp = $this->get('/register');
        $resp->assertStatus(200);
        $resp->assertDontSee('g-recaptcha');
    }

    public function test_captcha_shows_when_enabled_and_site_key_present(): void
    {
        $this->updateSetting('captcha.enabled', true);
        $this->updateSetting('captcha.site_key', 'test_site_key_123');
        $this->updateSetting('captcha.secret_key', 'test_secret_key_456');

        $resp = $this->get('/register');
        $resp->assertStatus(200);
        $resp->assertSee('g-recaptcha');
    }

    public function test_registration_fails_when_captcha_invalid(): void
    {
        $this->updateSetting('captcha.enabled', true);
        $this->updateSetting('captcha.site_key', 'test_site_key_123');
        $this->updateSetting('captcha.secret_key', 'test_secret_key_456');

        // Fake Google response as failure
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => false, 'error-codes' => ['invalid-input-response']], 200),
        ]);

        $data = $this->baseUserData();
        $data['g-recaptcha-response'] = 'bogus-token';

        $resp = $this->post('/register', $data);
        $resp->assertSessionHasErrors(['g-recaptcha-response']);
    }

    public function test_registration_passes_when_captcha_valid(): void
    {
        $this->updateSetting('captcha.enabled', true);
        $this->updateSetting('captcha.site_key', 'test_site_key_123');
        $this->updateSetting('captcha.secret_key', 'test_secret_key_456');

        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response(['success' => true], 200),
        ]);

        $data = $this->baseUserData();
        $data['g-recaptcha-response'] = 'valid-token';

        $resp = $this->post('/register', $data);
        $resp->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => $data['email']]);
    }
}
