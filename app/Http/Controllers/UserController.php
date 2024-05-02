<?php

namespace App\Http\Controllers;

use App\DTOs\User\UserCreationDto;
use App\Http\Requests\User\LoginCallbackOfProviderRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RedirectToLoginWithProviderRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Requests\User\SendPasswordResetLinkEmailRequest;
use App\Http\Resources\User\UserLoginResource;
use App\Http\Resources\User\UserResource;
use App\Models\Company;
use App\Services\Authentication\ProviderService;
use App\Services\Company\CompanyService;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserController extends Controller
{
    //@@TODO: This should be names ad AuthenticationController
    public function __construct(
        private readonly UserService $userService,
        private readonly ProviderService $providerService,
        private readonly CompanyService $companyService
    ) { }

    public function login(LoginRequest $request): Response
    {
        $this->userService->login($request->email, $request->password);
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
        $data = $request->only('name', 'email', 'password');
        $userCreationData = UserCreationDto::fromRequest($data);
        $this->userService->create($userCreationData);
        Company::create([
            'name' => $request->company_name
        ]);

        return response('', Response::HTTP_CREATED);
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
