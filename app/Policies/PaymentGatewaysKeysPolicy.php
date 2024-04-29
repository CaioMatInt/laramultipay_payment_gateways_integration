<?php

namespace App\Policies;

use App\Models\PaymentGatewayKey;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentGatewaysKeysPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {

    }

    public function view(User $user, PaymentGatewayKey $paymentGatewaysKeys)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, PaymentGatewayKey $paymentGatewaysKeys)
    {
    }

    public function delete(User $user, PaymentGatewayKey $paymentGatewaysKeys)
    {
    }

    public function restore(User $user, PaymentGatewayKey $paymentGatewaysKeys)
    {
    }

    public function forceDelete(User $user, PaymentGatewayKey $paymentGatewaysKeys)
    {
    }
}
