<?php

namespace Authentication;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Traits\ExternalProviderTrait;
use Tests\Traits\ProviderTrait;
use Tests\Traits\SocialiteTrait;
use Tests\Traits\UserTrait;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use UserTrait;
    use ProviderTrait;
    use ExternalProviderTrait;
    use SocialiteTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockVariables();
    }

    private function mockUsers(): void
    {
        $this->mockUser();
        $this->mockUserWithUnverifiedEmail();
    }

    private function mockVariables()
    {
        $this->mockUsers();
        $this->mockProviders();
        $this->mockExternalProviderResponses();
    }

    public function test_ensure_a_valid_and_verified_user_can_login()
    {
        $userCredentials['email'] = $this->user->email;
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);
        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'name'
        ]);
    }

    public function test_cant_login_without_sending_password()
    {
        $userCredentials['email'] = $this->user->email;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
    }

    public function test_cant_login_without_sending_email()
    {
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'The email field is required.'
        ]);
    }

    public function test_cant_login_with_unverified_email()
    {
        Session::start();

        $userCredentials['email'] = $this->userWithoutUnverifiedEmail->email;
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'Your email address is not verified. Please, check your inbox.'
        ]);
    }

    public function test_cant_login_with_invalid_password()
    {
        $userCredentials['email'] = $this->user->email;
        $userCredentials['password'] = 'invalidPassword';

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    }

    public function test_cant_login_with_invalid_email()
    {
        Session::start();

        $userCredentials['email'] = 'email@mail.com';
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    }

    public function test_can_register_user_with_valid_data()
    {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name', 'email']);
        $userData = array_merge($userData, $this->getDefaultPasswordAndConfirmationPassword());

        $this->postJson(route('user.register'), $userData)->assertCreated();
    }

    public function test_cant_register_user_without_email()
    {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name']);
        $userData = array_merge($userData, $this->getDefaultPasswordAndConfirmationPassword());

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'email' => 'The email field is required.'
        ]);
    }

    public function test_cant_register_user_without_name()
    {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['email']);
        $userData['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    }

    public function test_cant_register_user_without_password()
    {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['email', 'name']);

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
    }

    public function test_cant_register_user_with_an_email_that_is_already_in_use()
    {
        $alreadyExistingUser = User::factory()->create();

        $newUserFactoryData = User::factory()->make();
        $newUserData = $newUserFactoryData->only(['name']);
        $newUserData['email'] = $alreadyExistingUser->email;
        $newUserData = array_merge($newUserData, $this->getDefaultPasswordAndConfirmationPassword());

        $response = $this->postJson(route('user.register'), $newUserData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test_cant_register_user_with_an_invalid_email()
    {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name']);
        $userData['email'] = 'invalid-email';
        $userData['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->postJson(route('user.register'), $userData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        $this->assertEquals(
            'The email field must be a valid email address.',
            $response->json('errors.email.0')
        );
    }

    public function test_cant_register_user_with_a_name_longer_than_255_characters()
    {
        $userData['name'] = str_repeat('a', 256);
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '12345678';
        $userData['password_confirmation'] = '12345678';

        $response = $this->postJson(route('user.register'), $userData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $this->assertEquals(
            'The name field must not be greater than 255 characters.',
            $response->json('errors.name.0')
        );
    }

    public function test_cant_register_user_with_an_integer_name()
    {
        $userData['name'] = 123;
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '12345678';
        $userData['password_confirmation'] = '12345678';

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        $this->assertEquals(
            'The name field must be a string.',
            $response->json('errors.name.0')
        );
    }

    public function test_cant_register_with_a_password_with_less_than_8_characters()
    {
        $userData['name'] = 'Test User';
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '1234567';
        $userData['password_confirmation'] = '1234567';

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
        $this->assertEquals(
            'The password field must be at least 8 characters.',
            $response->json('errors.password.0')
        );
    }

    public function test_ensure_a_non_authenticated_user_cannot_logout()
    {
        $response = $this->json('POST', route('user.logout'));

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_logout()
    {
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->json('POST', route('user.logout'));
        $response->assertNoContent();
    }

    public function test_can_show_current_authenticated_user()
    {
        $response = $this->actingAs($this->user)->get(route('user.me'));
        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_cant_show_current_authenticated_user_when_not_logged_in()
    {
        $this->json('get', route('user.me'))->assertUnauthorized();
    }

    public function test_cant_ask_for_recovering_email_when_there_is_no_user_registered_with_it()
    {
        $response = $this->json('POST', route('user.password.forgot'), [
            'email' => 'invalid@email.com'
        ]);

        $response->assertUnprocessable();
        $this->assertEquals(
            'The selected email is invalid.',
            $response->json('errors.email.0')
        );
    }

    public function test_can_ask_for_email_recovering_when_sending_an_email_that_there_is_a_user_registered_with_it()
    {
        $user = $this->user;
        $response = $this->post(route('user.password.forgot'), [
            'email' => $user->email
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'message'
        ]);

        $this->assertEquals("We have emailed your password reset link.", $response['message']);
    }

    public function test_can_reset_password_when_token_is_valid()
    {
        $user = $this->user;
        $token = Password::broker()->createToken($user);
        $newPassword = '12345678';

        $response = $this->postJson(route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'message'
        ]);
        $response->assertJson([
            'message' => 'Your password has been reset.'
        ]);
    }

    public function test_cant_reset_password_when_token_is_invalid()
    {
        $user = $this->user;
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertStatus(422)->assertJsonStructure([
            'message'
        ]);

        $this->assertEquals("This password reset token is invalid.", $response['message']);
    }

    public function test_cant_reset_password_when_email_is_not_valid()
    {
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => 'invalid-token',
            'email' => 'invalid@invalid.com',
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertUnprocessable();
        $this->assertEquals(
            'The selected email is invalid.',
            $response->json('errors.email.0')
        );
    }

    public function test_cant_reset_password_when_password_has_less_than_8_characters()
    {
        $user = $this->user;
        $token = Password::broker()->createToken($user);
        $newPassword = '1234567';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertUnprocessable();
        $this->assertEquals(
            'The password field must be at least 8 characters.',
            $response->json('errors.password.0')
        );
    }

    public function test_cant_reset_password_when_confirming_a_wrong_password()
    {
        $user = $this->user;
        $token = Password::broker()->createToken($user);
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => 'invalid-password'
        ]);

        $response->assertUnprocessable();

        $this->assertEquals(
            'The password field confirmation does not match.',
            $response->json('errors.password.0')
        );
    }

    public function test_cant_reset_password_when_not_confirming_password()
    {
        $user = $this->user;
        $token = Password::broker()->createToken($user);
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword
        ]);

        $response->assertUnprocessable();

        $this->assertEquals(
            'The password confirmation field is required.',
            $response->json('errors.password_confirmation.0')
        );
    }

    public function test_cant_reset_password_when_not_sending_token()
    {
        $user = $this->user;
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertUnprocessable();

        $this->assertEquals(
            'The token field is required.',
            $response->json('errors.token.0')
        );
    }

    public function test_cant_reset_password_with_expired_token()
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $password = 'newpassword';

        DB::table('password_reset_tokens')->where('email', $user->email)->update([
            'created_at' => now()->subHours(2)
        ]);

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message'
        ]);
        $this->assertEquals("This password reset token is invalid.", $response->json('message'));
    }

    public function test_cant_redirect_to_login_with_provider_using_an_invalid_provider_name()
    {
        $response = $this->get(route('user.login') . '/' . 'invalid-provider');


        $response->assertSessionHasErrors([
            'provider_name' => 'The selected provider name is invalid.'
        ]);
    }

    public function test_should_redirect_to_login_with_provider_using_a_valid_provider_name()
    {
        $providers = config('auth.third_party_login_providers');

        if ($providers) {
            $provider = array_key_first($providers);
            $response = $this->get(route('user.login') . '/' . $provider);
            $response->assertRedirect();
        }
    }

    public function test_ensure_a_registered_user_with_google_provider_can_login()
    {
        User::factory()->make([
            'name' => $this->googleResponse->name,
            'email' => $this->googleResponse->email,
            'provider_id' => $this->googleProvider->id,
            'external_provider_id' => $this->googleResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->googleResponse);

        $this->get(route('user.login.provider.callback', $this->googleProvider->name));

        $this->assertTrue(Auth::check());
    }

    public function test_ensure_a_registered_user_with_facebook_provider_can_login()
    {
        User::factory()->make([
            'name' => $this->facebookResponse->name,
            'email' => $this->facebookResponse->email,
            'provider_id' => $this->facebookProvider->id,
            'external_provider_id' => $this->facebookResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->facebookResponse);

        $this->get(route('user.login.provider.callback', $this->facebookProvider->name));
        $this->assertTrue(Auth::check());
    }

    public function test_ensure_a_registered_user_with_github_provider_can_login()
    {
        User::factory()->make([
            'name' => $this->githubResponse->name,
            'email' => $this->githubResponse->email,
            'provider_id' => $this->githubProvider->id,
            'external_provider_id' => $this->githubResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->githubResponse);

        $res = $this->get(route('user.login.provider.callback', $this->githubProvider->name));
        $this->assertTrue(Auth::check());
    }

    public function test_ensure_an_unregistered_user_that_has_logged_with_google_gets_an_account_and_log_in()
    {
        $this->makeSocialiteServiceStub('login', $this->googleResponse);

        $this->get(route('user.login.provider.callback', $this->googleProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->googleProvider->id,
            'external_provider_id' => $this->googleResponse->id,
            'name' => $this->googleResponse->name,
            'email' => $this->googleResponse->email
        ]);
        $this->assertTrue(Auth::check());
    }

    public function test_ensure_an_unregistered_user_that_has_logged_with_facebook_gets_an_account_and_log_in()
    {
        $this->makeSocialiteServiceStub('login', $this->facebookResponse);

        $this->get(route('user.login.provider.callback', $this->facebookProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->facebookProvider->id,
            'external_provider_id' => $this->facebookResponse->id,
            'name' => $this->facebookResponse->name,
            'email' => $this->facebookResponse->email
        ]);
        $this->assertTrue(Auth::check());
    }

    public function test_ensure_an_unregistered_user_that_has_used_logged_with_github_gets_an_account_and_log_in()
    {
        $this->makeSocialiteServiceStub('login', $this->githubResponse);

        $this->get(route('user.login.provider.callback', $this->githubProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->githubProvider->id,
            'external_provider_id' => $this->githubResponse->id,
            'name' => $this->githubResponse->nickname,
            'email' => $this->githubResponse->email
        ]);
        $this->assertTrue(Auth::check());
    }

    public function test_cannot_login_with_invalid_provider()
    {
        $response = $this->get(route('user.login.provider.callback', 'googlew'));
        $response->assertSessionHasErrors([
            'provider_name' => 'The selected provider name is invalid.'
        ]);
    }

    public function test_cant_login_with_a_different_provider_as_the_registered()
    {
        $this->expectException(\Exception::class);

        User::factory()->create([
            'name' => $this->googleResponse->name,
            'email' => 'random_email@gmail.com',
            'provider_id' => $this->googleProvider->id,
            'external_provider_id' => $this->googleResponse->id,
        ]);

        $githubResponseWithDifferentEmail = $this->githubResponse;
        $githubResponseWithDifferentEmail->email = 'random_email@gmail.com';

        $googleResponseWithDifferentEmail = $this->googleResponse;
        $googleResponseWithDifferentEmail->email = 'random_email@gmail.com';

        $this->makeSocialiteServiceStubWithMultipleResponses('login', [
            ['google', $googleResponseWithDifferentEmail],
            ['github', $githubResponseWithDifferentEmail],
        ]);

        $this->get(route('user.login.provider.callback', $this->githubProvider->name))->json();
        $this->assertFalse(Auth::check());
    }
}
