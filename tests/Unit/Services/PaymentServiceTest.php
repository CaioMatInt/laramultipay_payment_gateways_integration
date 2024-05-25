<?php

use App\Contracts\ModelAware;
use App\Models\Company;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Illuminate\Pagination\LengthAwarePaginator;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('PaymentServiceTest', function () {

    beforeEach(function () {
        $this->paymentService = app(PaymentService::class);
    });

    test('should implement ModelAware interface', function () {
        expect($this->paymentService)->toBeInstanceOf(ModelAware::class);
    });

    test('should return a LengthAwarePaginator when getting all payments of a company', function () {
        $company = Company::factory()->create();
        $perPage = 15;
        $payments = $this->paymentService->getPaginatedByCompanyId($company->id, $perPage);
        expect($payments)->toBeInstanceOf(LengthAwarePaginator::class);
    });

    test('should return all payments of a company', function () {
        $company = Company::factory()->create();
        $totalPaymentsToCreate = 5;

        $createdPayments = Payment::factory()->count($totalPaymentsToCreate)->create([
            'company_id' => $company->id
        ]);

        $payments = $this->paymentService->getPaginatedByCompanyId($company->id);

        expect($payments->count())->toBe($totalPaymentsToCreate);

        foreach ($payments as $key => $payment) {
            expect($payment->name)->toBe($createdPayments[$key]->name)
                ->and($payment->user_id)->toBe($createdPayments[$key]->user_id)
                ->and($payment->company_id)->toBe($createdPayments[$key]->company_id)
                ->and($payment->payment_generic_status_id)->toBe($createdPayments[$key]->payment_generic_status_id)
                ->and($payment->payment_method_id)->toBe($createdPayments[$key]->payment_method_id)
                ->and($payment->amount)->toBe($createdPayments[$key]->amount)
                ->and($payment->currency)->toBe($createdPayments[$key]->currency)
                ->and(
                    $payment
                        ->expires_at
                        ->toDateTimeString())
                        ->toBe($createdPayments[$key]->expires_at->toDateTimeString()
                );
        }
    });

    test('should return 15 payments per page by default', function () {
        $company = Company::factory()->create();
        $totalPaymentsToCreate = 20;

        Payment::factory()->count($totalPaymentsToCreate)->create([
            'company_id' => $company->id
        ]);

        $payments = $this->paymentService->getPaginatedByCompanyId($company->id);

        expect($payments->perPage())->toBe(15);
    });
});
