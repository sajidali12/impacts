<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function __construct(
        private StripeService $stripeService
    ) {}

    public function handle(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook.secret');

        if (!$endpointSecret) {
            Log::error('Stripe webhook endpoint secret not configured');
            return response('Webhook endpoint secret not configured', 400);
        }

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $endpointSecret
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response('Invalid signature', 400);
        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response('Webhook error', 400);
        }

        try {
            $handled = $this->stripeService->handleWebhook($event->toArray());
            
            if ($handled) {
                Log::info('Stripe webhook handled successfully', [
                    'event_type' => $event->type,
                    'event_id' => $event->id,
                ]);
                return response('Webhook handled', 200);
            } else {
                Log::info('Stripe webhook event ignored', [
                    'event_type' => $event->type,
                    'event_id' => $event->id,
                ]);
                return response('Event type not handled', 200);
            }
        } catch (\Exception $e) {
            Log::error('Error processing Stripe webhook', [
                'event_type' => $event->type,
                'event_id' => $event->id,
                'error' => $e->getMessage(),
            ]);
            return response('Processing error', 500);
        }
    }
}