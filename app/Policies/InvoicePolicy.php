<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view their own invoices
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin() || $invoice->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->isAdmin();
    }

    public function pay(User $user, Invoice $invoice): bool
    {
        return $invoice->user_id === $user->id && $invoice->status === 'pending';
    }
}