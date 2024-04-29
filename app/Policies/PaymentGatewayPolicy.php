<?php

namespace App\Policies;

use App\Models\PaymentGateway;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentGatewayPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {

    }

    public function view(User $user, PaymentGateway $paymentGateway)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, PaymentGateway $paymentGateway)
    {
    }

    public function delete(User $user, PaymentGateway $paymentGateway)
    {
    }

    public function restore(User $user, PaymentGateway $paymentGateway)
    {
    }

    public function forceDelete(User $user, PaymentGateway $paymentGateway)
    {
    }
}
