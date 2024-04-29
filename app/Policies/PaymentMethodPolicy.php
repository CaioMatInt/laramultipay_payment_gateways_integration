<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentMethodPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {

    }

    public function view(User $user, PaymentMethod $paymentMethod)
    {
    }

    public function create(User $user)
    {
    }

    public function update(User $user, PaymentMethod $paymentMethod)
    {
    }

    public function delete(User $user, PaymentMethod $paymentMethod)
    {
    }

    public function restore(User $user, PaymentMethod $paymentMethod)
    {
    }

    public function forceDelete(User $user, PaymentMethod $paymentMethod)
    {
    }
}
