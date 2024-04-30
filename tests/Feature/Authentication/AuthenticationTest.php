<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Tests\Traits\UserTrait::class);
uses(\Tests\Traits\ProviderTrait::class);
uses(\Tests\Traits\ExternalProviderTrait::class);
uses(\Tests\Traits\SocialiteTrait::class);

beforeEach(function () {
    test()->mockUserWithUnverifiedEmail();
    test()->mockUser();
    test()->mockProviders();
    test()->mockExternalProviderResponses();
});

describe('auth', function () {


    test('ensure a valid and verified user can login', function () {

        $userCredentials['email'] = $this->user->email;

        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);
        $response->assertOk();
        $response->assertJsonStructure([
            'access_token',
            'name'
        ]);
    });

    test('cant login without sending password', function () {
        $userCredentials['email'] = $this->user->email;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
    });

    test('cant login without sending email', function () {
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'The email field is required.'
        ]);
    });

    test('cant login with unverified email', function () {
        Session::start();

        $userCredentials['email'] = $this->userWithUnverifiedEmail->email;
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'Your email address is not verified. Please, check your inbox.'
        ]);
    });

    test('cant login with invalid password', function () {
        $userCredentials['email'] = $this->user->email;
        $userCredentials['password'] = 'invalidPassword';

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    });

    test('cant login with invalid email', function () {
        Session::start();

        $userCredentials['email'] = 'email@mail.com';
        $userCredentials['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.login'), $userCredentials);

        $response->assertSessionHasErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    });

    test('can register user with valid data', function () {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name', 'email']);
        $userData = array_merge($userData, $this->getDefaultPasswordAndConfirmationPassword());

        $this->postJson(route('user.register'), $userData)->assertCreated();
    });

    test('cant register user without email', function () {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name']);
        $userData = array_merge($userData, $this->getDefaultPasswordAndConfirmationPassword());

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'email' => 'The email field is required.'
        ]);
    });

    test('cant register user without name', function () {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['email']);
        $userData['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'name' => 'The name field is required.'
        ]);
    });

    test('cant register user without password', function () {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['email', 'name']);

        $response = $this->post(route('user.register'), $userData);

        $response->assertSessionHasErrors([
            'password' => 'The password field is required.'
        ]);
    });

    test('cant register user with an email that is already in use', function () {
        $alreadyExistingUser = User::factory()->create();

        $newUserFactoryData = User::factory()->make();
        $newUserData = $newUserFactoryData->only(['name']);
        $newUserData['email'] = $alreadyExistingUser->email;
        $newUserData = array_merge($newUserData, $this->getDefaultPasswordAndConfirmationPassword());

        $response = $this->postJson(route('user.register'), $newUserData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    });

    test('cant register user with an invalid email', function () {
        $userFactoryData = User::factory()->make();
        $userData = $userFactoryData->only(['name']);
        $userData['email'] = 'invalid-email';
        $userData['password'] = $this->unhashedDefaultUserPassword;

        $response = $this->postJson(route('user.register'), $userData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
        expect($response->json('errors.email.0'))->toEqual('The email field must be a valid email address.');
    });

    test('cant register user with a name longer than 255 characters', function () {
        $userData['name'] = str_repeat('a', 256);
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '12345678';
        $userData['password_confirmation'] = '12345678';

        $response = $this->postJson(route('user.register'), $userData);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        expect($response->json('errors.name.0'))->toEqual('The name field must not be greater than 255 characters.');
    });

    test('cant register user with an integer name', function () {
        $userData['name'] = 123;
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '12345678';
        $userData['password_confirmation'] = '12345678';

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
        expect($response->json('errors.name.0'))->toEqual('The name field must be a string.');
    });

    test('cant register with a password with less than 8 characters', function () {
        $userData['name'] = 'Test User';
        $userData['email'] = 'email@mail.com';
        $userData['password'] = '1234567';
        $userData['password_confirmation'] = '1234567';

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
        expect($response->json('errors.password.0'))->toEqual('The password field must be at least 8 characters.');
    });

    test('ensure a non authenticated user cannot logout', function () {
        $response = $this->json('POST', route('user.logout'));

        $response->assertUnauthorized();
    });

    test('authenticated user can logout', function () {
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->json('POST', route('user.logout'));
        $response->assertNoContent();
    });

    test('can show current authenticated user', function () {
        $response = $this->actingAs($this->user)->get(route('user.me'));
        $response->assertOk();
        $response->assertJsonStructure([
            'id',
            'name',
            'created_at',
            'updated_at',
        ]);
    });

    test('cant show current authenticated user when not logged in', function () {
        $this->json('get', route('user.me'))->assertUnauthorized();
    });

    test('cant ask for recovering email when there is no user registered with it', function () {
        $response = $this->json('POST', route('user.password.forgot'), [
            'email' => 'invalid@email.com'
        ]);

        $response->assertUnprocessable();
        expect($response->json('errors.email.0'))->toEqual('The selected email is invalid.');
    });

    test('can ask for email recovering when sending an email that there is a user registered with it', function () {
        $user = $this->user;
        $response = $this->post(route('user.password.forgot'), [
            'email' => $user->email
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'message'
        ]);

        expect($response['message'])->toEqual("We have emailed your password reset link.");
    });

    test('can reset password when token is valid', function () {
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
    });

    test('cant reset password when token is invalid', function () {
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

        expect($response['message'])->toEqual("This password reset token is invalid.");
    });

    test('cant reset password when email is not valid', function () {
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => 'invalid-token',
            'email' => 'invalid@invalid.com',
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertUnprocessable();
        expect($response->json('errors.email.0'))->toEqual('The selected email is invalid.');
    });

    test('cant reset password when password has less than 8 characters', function () {
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
        expect($response->json('errors.password.0'))->toEqual('The password field must be at least 8 characters.');
    });

    test('cant reset password when confirming a wrong password', function () {
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

        expect($response->json('errors.password.0'))->toEqual('The password field confirmation does not match.');
    });

    test('cant reset password when not confirming password', function () {
        $user = $this->user;
        $token = Password::broker()->createToken($user);
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword
        ]);

        $response->assertUnprocessable();

        expect($response->json('errors.password_confirmation.0'))->toEqual('The password confirmation field is required.');
    });

    test('cant reset password when not sending token', function () {
        $user = $this->user;
        $newPassword = '12345678';

        $response = $this->json('POST', route('user.password.reset'), [
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword
        ]);

        $response->assertUnprocessable();

        expect($response->json('errors.token.0'))->toEqual('The token field is required.');
    });

    test('cant reset password with expired token', function () {
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
        expect($response->json('message'))->toEqual("This password reset token is invalid.");
    });

    test('cant redirect to login with provider using an invalid provider name', function () {
        $response = $this->get(route('user.login') . '/' . 'invalid-provider');

        $response->assertSessionHasErrors([
            'provider_name' => 'The selected provider name is invalid.'
        ]);
    });

    test('should redirect to login with provider using a valid provider name', function () {
        $providers = config('auth.third_party_login_providers');

        if ($providers) {
            $provider = array_key_first($providers);
            $response = $this->get(route('user.login') . '/' . $provider);
            $response->assertRedirect();
        }
    });

    test('ensure a registered user with google provider can login', function () {
        User::factory()->make([
            'name' => $this->googleResponse->name,
            'email' => $this->googleResponse->email,
            'provider_id' => $this->googleProvider->id,
            'external_provider_id' => $this->googleResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->googleResponse);

        $this->get(route('user.login.provider.callback', $this->googleProvider->name));

        expect(Auth::check())->toBeTrue();
    });

    test('ensure a registered user with facebook provider can login', function () {
        User::factory()->make([
            'name' => $this->facebookResponse->name,
            'email' => $this->facebookResponse->email,
            'provider_id' => $this->facebookProvider->id,
            'external_provider_id' => $this->facebookResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->facebookResponse);

        $this->get(route('user.login.provider.callback', $this->facebookProvider->name));
        expect(Auth::check())->toBeTrue();
    });

    test('ensure a registered user with github provider can login', function () {
        User::factory()->make([
            'name' => $this->githubResponse->name,
            'email' => $this->githubResponse->email,
            'provider_id' => $this->githubProvider->id,
            'external_provider_id' => $this->githubResponse->id,
        ]);

        $this->makeSocialiteServiceStub('login', $this->githubResponse);

        $res = $this->get(route('user.login.provider.callback', $this->githubProvider->name));
        expect(Auth::check())->toBeTrue();
    });

    test('ensure an unregistered user that has logged with google gets an account and log in', function () {
        $this->makeSocialiteServiceStub('login', $this->googleResponse);

        $this->get(route('user.login.provider.callback', $this->googleProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->googleProvider->id,
            'external_provider_id' => $this->googleResponse->id,
            'name' => $this->googleResponse->name,
            'email' => $this->googleResponse->email
        ]);
        expect(Auth::check())->toBeTrue();
    });

    test('ensure an unregistered user that has logged with facebook gets an account and log in', function () {
        $this->makeSocialiteServiceStub('login', $this->facebookResponse);

        $this->get(route('user.login.provider.callback', $this->facebookProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->facebookProvider->id,
            'external_provider_id' => $this->facebookResponse->id,
            'name' => $this->facebookResponse->name,
            'email' => $this->facebookResponse->email
        ]);
        expect(Auth::check())->toBeTrue();
    });

    test('ensure an unregistered user that has used logged with github gets an account and log in', function () {
        $this->makeSocialiteServiceStub('login', $this->githubResponse);

        $this->get(route('user.login.provider.callback', $this->githubProvider->name));

        $this->assertDatabaseHas('users', [
            'provider_id' => $this->githubProvider->id,
            'external_provider_id' => $this->githubResponse->id,
            'name' => $this->githubResponse->nickname,
            'email' => $this->githubResponse->email
        ]);
        expect(Auth::check())->toBeTrue();
    });

    test('cannot login with invalid provider', function () {
        $response = $this->get(route('user.login.provider.callback', 'googlew'));
        $response->assertSessionHasErrors([
            'provider_name' => 'The selected provider name is invalid.'
        ]);
    });

    test('cant login with a different provider as the registered', function () {
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
        expect(Auth::check())->toBeFalse();
    });

});
