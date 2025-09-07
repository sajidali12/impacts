<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\WeeklyAnalytic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServiceProviderController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        
        $stats = [
            'total_services' => $user->services()->count(),
            'active_services' => $user->services()->active()->count(),
            'total_views_this_month' => $user->services()->sum('view_count'),
            'total_leads_this_month' => $user->leads()->thisMonth()->count(),
            'conversion_rate' => $this->calculateConversionRate($user),
            'pending_invoices' => $user->invoices()->pending()->count(),
            'overdue_invoices' => $user->invoices()->overdue()->count(),
        ];

        $recentLeads = $user->leads()
            ->with('leadable')
            ->latest()
            ->limit(5)
            ->get();

        $recentServices = $user->services()
            ->latest()
            ->limit(5)
            ->get();

        $weeklyAnalytics = WeeklyAnalytic::forUser($user->id)
            ->orderBy('week_start', 'desc')
            ->limit(4)
            ->get();

        return view('service-provider.dashboard', compact('stats', 'recentLeads', 'recentServices', 'weeklyAnalytics'));
    }

    public function index(): View
    {
        $services = Auth::user()->services()
            ->latest()
            ->paginate(12);

        return view('service-provider.services.index', compact('services'));
    }

    public function create(): View
    {
        $serviceTypes = [
            'solicitor' => 'Solicitor',
            'mortgage_advisor' => 'Mortgage Advisor',
            'technical_specialist' => 'Technical Specialist',
            'surveyor' => 'Surveyor',
            'architect' => 'Architect',
            'financial_advisor' => 'Financial Advisor',
            'estate_agent' => 'Estate Agent',
            'insurance' => 'Insurance',
            'other' => 'Other',
        ];

        return view('service-provider.services.create', compact('serviceTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_type' => 'required|string|max:100',
            'pricing' => 'nullable|numeric|min:0',
            'pricing_type' => 'required|in:fixed,hourly,consultation',
            'service_areas' => 'nullable|array',
            'service_areas.*' => 'string|max:255',
            'external_url' => 'nullable|url|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('services/logos', 'public');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('services', 'public');
                $images[] = $path;
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['logo'] = $logoPath;
        $validated['images'] = $images;
        $validated['service_areas'] = $request->input('service_areas', []);
        $validated['is_featured'] = $request->boolean('is_featured');

        Service::create($validated);

        return redirect()
            ->route('service-provider.services.index')
            ->with('success', 'Service created successfully.');
    }

    public function show(Service $service): View
    {
        $this->authorize('view', $service);

        $leads = $service->leads()
            ->latest()
            ->paginate(10);

        return view('service-provider.services.show', compact('service', 'leads'));
    }

    public function edit(Service $service): View
    {
        $this->authorize('update', $service);

        $serviceTypes = [
            'solicitor' => 'Solicitor',
            'mortgage_advisor' => 'Mortgage Advisor',
            'technical_specialist' => 'Technical Specialist',
            'surveyor' => 'Surveyor',
            'architect' => 'Architect',
            'financial_advisor' => 'Financial Advisor',
            'estate_agent' => 'Estate Agent',
            'insurance' => 'Insurance',
            'other' => 'Other',
        ];

        return view('service-provider.services.edit', compact('service', 'serviceTypes'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $this->authorize('update', $service);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_type' => 'required|string|max:100',
            'pricing_type' => 'required|string|in:hourly,fixed,project,consultation',
            'pricing_amount' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'availability' => 'nullable|string|in:immediate,within_week,within_month,flexible',
            'specializations' => 'nullable|string|max:500',
            'external_link' => 'nullable|url|max:500',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image management
        $images = $service->images ?? [];
        
        // Remove selected images
        if ($request->has('remove_images')) {
            $imagesToRemove = $request->input('remove_images', []);
            foreach ($imagesToRemove as $imageToRemove) {
                // Remove from storage
                if (Storage::disk('public')->exists($imageToRemove)) {
                    Storage::disk('public')->delete($imageToRemove);
                }
                // Remove from array
                $images = array_filter($images, function($image) use ($imageToRemove) {
                    return $image !== $imageToRemove;
                });
            }
            // Re-index array
            $images = array_values($images);
        }
        
        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('services', 'public');
                $images[] = $path;
            }
        }

        $validated['images'] = $images;
        $validated['service_areas'] = $request->input('service_areas', []);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $service->update($validated);

        return redirect()
            ->route('service-provider.services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->authorize('delete', $service);

        if ($service->logo) {
            Storage::disk('public')->delete($service->logo);
        }

        if ($service->images) {
            foreach ($service->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $service->delete();

        return redirect()
            ->route('service-provider.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    public function analytics(): View
    {
        $user = Auth::user();
        
        $weeklyAnalytics = WeeklyAnalytic::forUser($user->id)
            ->orderBy('week_start', 'desc')
            ->limit(12)
            ->get();

        $monthlyStats = $this->getMonthlyStats($user);
        $topPerformingServices = $this->getTopPerformingServices($user);

        return view('service-provider.analytics', compact('weeklyAnalytics', 'monthlyStats', 'topPerformingServices'));
    }

    public function invoices(): View
    {
        $invoices = Auth::user()->invoices()
            ->latest()
            ->paginate(10);

        return view('service-provider.invoices', compact('invoices'));
    }

    public function viewInvoice(Invoice $invoice): View
    {
        $this->authorize('view', $invoice);

        return view('service-provider.invoice-view', compact('invoice'));
    }

    public function downloadInvoice(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        try {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('invoices.pdf', compact('invoice'));
            return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            return redirect()
                ->route('service-provider.invoices')
                ->with('error', 'Unable to generate PDF. Please try again later.');
        }
    }

    public function payInvoice(Invoice $invoice): RedirectResponse
    {
        $this->authorize('view', $invoice);

        if ($invoice->status !== 'pending') {
            return redirect()
                ->route('service-provider.invoices')
                ->with('error', 'This invoice cannot be paid.');
        }

        try {
            $stripeService = app(\App\Services\StripeService::class);
            $paymentIntent = $stripeService->createPaymentIntent($invoice);
            
            return redirect()
                ->route('service-provider.invoices')
                ->with('payment_intent', $paymentIntent)
                ->with('success', 'Payment processing initialized. Please complete payment.');
        } catch (\Exception $e) {
            return redirect()
                ->route('service-provider.invoices')
                ->with('error', 'Failed to initialize payment: ' . $e->getMessage());
        }
    }

    public function paymentReturn(Request $request): RedirectResponse
    {
        $paymentIntentId = $request->query('payment_intent_id') ?: $request->query('payment_intent');
        $paymentIntentClientSecret = $request->query('payment_intent_client_secret');
        
        \Log::info('Payment return parameters', [
            'all_params' => $request->all(),
            'payment_intent_id' => $paymentIntentId,
        ]);
        
        if (!$paymentIntentId) {
            return redirect()
                ->route('service-provider.invoices')
                ->with('error', 'Invalid payment return - missing payment intent ID.');
        }

        try {
            $stripeService = app(\App\Services\StripeService::class);
            $success = $stripeService->confirmPayment($paymentIntentId);
            
            if ($success) {
                return redirect()
                    ->route('service-provider.invoices')
                    ->with('success', 'Payment completed successfully! Your invoice has been updated.');
            } else {
                return redirect()
                    ->route('service-provider.invoices')
                    ->with('error', 'Payment could not be confirmed. Please contact support if your card was charged.');
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('service-provider.invoices')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    private function calculateConversionRate($user): float
    {
        $totalViews = $user->services()->sum('view_count');
        $totalLeads = $user->leads()->count();

        return $totalViews > 0 ? round(($totalLeads / $totalViews) * 100, 2) : 0;
    }

    private function getMonthlyStats($user): array
    {
        $thisMonth = $user->leads()->thisMonth()->count();
        $lastMonth = $user->leads()->lastMonth()->count();
        
        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change' => $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0,
        ];
    }

    private function getTopPerformingServices($user): array
    {
        return $user->services()
            ->orderBy('lead_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
}