<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\User;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(Invoice $invoice): array
    {
        try {
            $customer = $this->getOrCreateCustomer($invoice->user);

            $paymentIntent = PaymentIntent::create([
                'amount' => $invoice->total_amount * 100, // Convert to cents
                'currency' => 'gbp',
                'customer' => $customer->id,
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'user_id' => $invoice->user_id,
                ],
                'description' => "Payment for invoice {$invoice->invoice_number}",
            ]);

            $invoice->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            return [
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    public function confirmPayment(string $paymentIntentId): bool
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            if ($paymentIntent->status === 'succeeded') {
                $invoice = Invoice::where('stripe_payment_intent_id', $paymentIntentId)->first();
                
                if ($invoice) {
                    $invoice->markAsPaid([
                        'stripe_payment_intent_id' => $paymentIntentId,
                        'payment_method' => $paymentIntent->payment_method,
                        'amount_received' => $paymentIntent->amount_received / 100,
                        'currency' => $paymentIntent->currency,
                        'paid_at' => now(),
                    ]);
                    
                    return true;
                }
            }
            
            return false;
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to confirm payment: ' . $e->getMessage());
        }
    }

    public function createSetupIntent(User $user): array
    {
        try {
            $customer = $this->getOrCreateCustomer($user);

            $setupIntent = \Stripe\SetupIntent::create([
                'customer' => $customer->id,
                'usage' => 'off_session',
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ]);

            return [
                'client_secret' => $setupIntent->client_secret,
                'setup_intent_id' => $setupIntent->id,
            ];
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create setup intent: ' . $e->getMessage());
        }
    }

    public function getPaymentMethods(User $user): array
    {
        try {
            $customer = $this->getOrCreateCustomer($user);

            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customer->id,
                'type' => 'card',
            ]);

            return $paymentMethods->data ?? [];
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve payment methods: ' . $e->getMessage());
        }
    }

    public function createInvoice(Invoice $invoice): string
    {
        try {
            $customer = $this->getOrCreateCustomer($invoice->user);

            $stripeInvoice = \Stripe\Invoice::create([
                'customer' => $customer->id,
                'collection_method' => 'send_invoice',
                'days_until_due' => 30,
                'metadata' => [
                    'invoice_id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                ],
                'description' => "Invoice {$invoice->invoice_number} for lead referrals",
            ]);

            // Add invoice items
            foreach ($invoice->lead_breakdown as $breakdown) {
                \Stripe\InvoiceItem::create([
                    'customer' => $customer->id,
                    'invoice' => $stripeInvoice->id,
                    'amount' => $breakdown['total'] * 100, // Convert to cents
                    'currency' => 'gbp',
                    'description' => "{$breakdown['count']} {$breakdown['type']} leads",
                ]);
            }

            $stripeInvoice->finalizeInvoice();
            $stripeInvoice->sendInvoice();

            $invoice->update([
                'stripe_invoice_id' => $stripeInvoice->id,
            ]);

            return $stripeInvoice->hosted_invoice_url;
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create Stripe invoice: ' . $e->getMessage());
        }
    }

    private function getOrCreateCustomer(User $user): Customer
    {
        try {
            // Try to find existing customer
            $customers = Customer::all([
                'email' => $user->email,
                'limit' => 1,
            ]);

            if (!empty($customers->data)) {
                return $customers->data[0];
            }

            // Create new customer
            return Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                    'role' => $user->role,
                ],
                'description' => "Customer for {$user->name} ({$user->role})",
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to get or create customer: ' . $e->getMessage());
        }
    }

    public function handleWebhook(array $payload): bool
    {
        try {
            $event = \Stripe\Event::constructFrom($payload);

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    return $this->confirmPayment($paymentIntent->id);

                case 'invoice.payment_succeeded':
                    $stripeInvoice = $event->data->object;
                    $invoice = Invoice::where('stripe_invoice_id', $stripeInvoice->id)->first();
                    
                    if ($invoice && $invoice->status !== 'paid') {
                        $invoice->markAsPaid([
                            'stripe_invoice_id' => $stripeInvoice->id,
                            'amount_paid' => $stripeInvoice->amount_paid / 100,
                            'currency' => $stripeInvoice->currency,
                            'paid_at' => now(),
                        ]);
                    }
                    return true;

                case 'invoice.payment_failed':
                    $stripeInvoice = $event->data->object;
                    $invoice = Invoice::where('stripe_invoice_id', $stripeInvoice->id)->first();
                    
                    if ($invoice) {
                        $invoice->update(['status' => 'overdue']);
                    }
                    return true;

                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error('Stripe webhook error: ' . $e->getMessage());
            return false;
        }
    }
}