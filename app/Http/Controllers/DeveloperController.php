<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\WeeklyAnalytic;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeveloperController extends Controller
{
    public function dashboard(): View
    {
        $user = Auth::user();
        
        $stats = [
            'total_properties' => $user->properties()->count(),
            'active_properties' => $user->properties()->active()->count(),
            'total_views_this_month' => $user->properties()->sum('view_count'),
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

        $recentProperties = $user->properties()
            ->latest()
            ->limit(5)
            ->get();

        $weeklyAnalytics = WeeklyAnalytic::forUser($user->id)
            ->orderBy('week_start', 'desc')
            ->limit(4)
            ->get();

        return view('developer.dashboard', compact('stats', 'recentLeads', 'recentProperties', 'weeklyAnalytics'));
    }

    public function index(): View
    {
        $properties = Auth::user()->properties()
            ->latest()
            ->paginate(12);

        return view('developer.properties.index', compact('properties'));
    }

    public function create(): View
    {
        return view('developer.properties.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'external_link' => 'nullable|url|max:500',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'boolean',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['images'] = $images;
        $validated['is_featured'] = $request->boolean('is_featured');

        Property::create($validated);

        return redirect()
            ->route('developer.properties.index')
            ->with('success', 'Property created successfully.');
    }

    public function show(Property $property): View
    {
        $this->authorize('view', $property);

        $leads = $property->leads()
            ->latest()
            ->paginate(10);

        return view('developer.properties.show', compact('property', 'leads'));
    }

    public function edit(Property $property): View
    {
        $this->authorize('update', $property);

        return view('developer.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $this->authorize('update', $property);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'property_type' => 'nullable|string|max:100',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'area' => 'nullable|numeric|min:0',
            'external_link' => 'nullable|url|max:500',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        // Handle image management
        $images = $property->images ?? [];
        
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
                $path = $image->store('properties', 'public');
                $images[] = $path;
            }
        }

        $validated['images'] = $images;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $property->update($validated);

        return redirect()
            ->route('developer.properties.show', $property)
            ->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property): RedirectResponse
    {
        $this->authorize('delete', $property);

        if ($property->images) {
            foreach ($property->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $property->delete();

        return redirect()
            ->route('developer.properties.index')
            ->with('success', 'Property deleted successfully.');
    }

    public function analytics(): View
    {
        $user = Auth::user();
        
        $weeklyAnalytics = WeeklyAnalytic::forUser($user->id)
            ->orderBy('week_start', 'desc')
            ->limit(12)
            ->get();

        $monthlyStats = $this->getMonthlyStats($user);
        $topPerformingProperties = $this->getTopPerformingProperties($user);

        return view('developer.analytics', compact('weeklyAnalytics', 'monthlyStats', 'topPerformingProperties'));
    }

    public function invoices(): View
    {
        $invoices = Auth::user()->invoices()
            ->latest()
            ->paginate(10);

        return view('developer.invoices', compact('invoices'));
    }

    public function payInvoice(Invoice $invoice): RedirectResponse
    {
        $this->authorize('view', $invoice);

        if ($invoice->status !== 'pending') {
            return redirect()
                ->route('developer.invoices')
                ->with('error', 'This invoice cannot be paid.');
        }

        try {
            $stripeService = app(\App\Services\StripeService::class);
            $paymentIntent = $stripeService->createPaymentIntent($invoice);
            
            return redirect()
                ->route('developer.invoices')
                ->with('payment_intent', $paymentIntent)
                ->with('success', 'Payment processing initialized. Please complete payment.');
        } catch (\Exception $e) {
            return redirect()
                ->route('developer.invoices')
                ->with('error', 'Failed to initialize payment: ' . $e->getMessage());
        }
    }

    private function calculateConversionRate($user): float
    {
        $totalViews = $user->properties()->sum('view_count');
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

    private function getTopPerformingProperties($user): array
    {
        return $user->properties()
            ->orderBy('lead_count', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }
}