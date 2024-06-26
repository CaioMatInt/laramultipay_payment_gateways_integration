<?php

namespace App\Http\Controllers;

use App\DTOs\Authentication\LoginDto;
use App\DTOs\User\UserCreationDto;
use App\Http\Requests\User\LoginCallbackOfProviderRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RedirectToLoginWithProviderRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\SendPasswordResetLinkEmailRequest;
use App\Http\Resources\User\UserLoginResource;
use App\Http\Resources\User\UserResource;
use App\Services\Authentication\ProviderService;
use App\Services\Company\CompanyService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class AuthenticationController extends Controller
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ProviderService $providerService,
        private readonly CompanyService $companyService,
    ) { }

    public function login(LoginRequest $request): Response
    {
        $loginDto = new LoginDto($request->only('email', 'password'));
        $this->userService->login($loginDto);
        $userToken = $this->userService->createUserToken();
        return response(UserLoginResource::make([
            'user' => auth()->user(),
            'token' => $userToken
        ]));
    }

    public function redirectToLoginWithProvider(RedirectToLoginWithProviderRequest $request): RedirectResponse
    {
        return $this->providerService->redirect($request->route('provider_name'));
    }

    /**
     * @throws \Exception
     */
    public function loginCallbackOfProvider(LoginCallbackOfProviderRequest $request): Response
    {
        $this->providerService->authenticateAndLogin($request->route('provider_name'));
        $userToken = $this->userService->createUserToken();

        return response(UserLoginResource::make([
            'user' => auth()->user(),
            'token' => $userToken
        ]));
    }

    public function register(RegisterRequest $request): Response
    {
        $userCreationData = new UserCreationDto($request->only('name', 'email', 'password'));
        $this->userService->create($userCreationData);
        $this->companyService->create($request->only('company_name'));

        return response('', SymfonyResponse::HTTP_CREATED);
    }

    public function getAuthenticatedUser(): Response
    {
        $userResource = new UserResource(auth()->user());
        return response($userResource);
    }

    public function logout(Request $request): Response
    {
        $this->userService->logout($request->user());
        return response([], Response::HTTP_NO_CONTENT);
    }

    public function sendPasswordResetLinkEmail(SendPasswordResetLinkEmailRequest $request): Response
    {
        return response([
            'message' => $this->userService->sendPasswordResetLinkEmail($request->email)
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): Response
    {
        $resetMessage = $this->userService->resetPassword(
            $request->email,
            $request->password,
            $request->password_confirmation,
            $request->token
        );

        return response(['message' => $resetMessage]);
    }
}
