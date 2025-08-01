<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Service;
use App\Models\PageView;
use App\Models\Lead;
use App\Models\HomepageBanner;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PublicController extends Controller
{
    public function index(): View
    {
        $siteSettings = HomepageBanner::getActive();
        
        $featuredProperties = Property::active()
            ->featured()
            ->with('user')
            ->limit(6)
            ->get();

        $featuredServices = Service::active()
            ->featured()
            ->with('user')
            ->limit(6)
            ->get();

        $recentProperties = Property::active()
            ->with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('public.home', compact('siteSettings', 'featuredProperties', 'featuredServices', 'recentProperties'));
    }

    public function properties(Request $request): View
    {
        $siteSettings = HomepageBanner::getActive();
        
        $query = Property::active()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $properties = $query->paginate(12)->appends($request->query());

        $propertyTypes = Property::active()
            ->distinct()
            ->whereNotNull('property_type')
            ->pluck('property_type')
            ->filter()
            ->sort()
            ->values();

        $locations = Property::active()
            ->distinct()
            ->whereNotNull('location')
            ->pluck('location')
            ->filter()
            ->sort()
            ->values();

        return view('public.properties', compact('siteSettings', 'properties', 'propertyTypes', 'locations'));
    }

    public function services(Request $request): View
    {
        $siteSettings = HomepageBanner::getActive();
        
        $query = Service::active()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('service_type')) {
            $query->byType($request->service_type);
        }

        if ($request->filled('service_area')) {
            $query->byArea($request->service_area);
        }

        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'rating':
                // TODO: Implement rating system
                $query->latest();
                break;
            default:
                $query->latest();
        }

        $services = $query->paginate(12)->appends($request->query());

        $serviceTypes = [
            'solicitor' => 'Solicitor',
            'mortgage_advisor' => 'Mortgage Advisor',
            'technical_specialist' => 'Technical Specialist',
            'surveyor' => 'Surveyor',
            'architect' => 'Architect',
            'financial_advisor' => 'Financial Advisor',
            'estate_agent' => 'Estate Agent',
            'insurance' => 'Insurance',
        ];

        $serviceAreas = Service::active()
            ->whereNotNull('service_areas')
            ->get()
            ->pluck('service_areas')
            ->flatten()
            ->unique()
            ->filter()
            ->sort()
            ->values();

        return view('public.services', compact('siteSettings', 'services', 'serviceTypes', 'serviceAreas'));
    }

    public function showProperty(Property $property): View
    {
        if (!$property->is_active || $property->archived_at) {
            abort(404);
        }

        $this->trackPageView($property);

        $similarProperties = Property::active()
            ->where('id', '!=', $property->id)
            ->where('user_id', $property->user_id)
            ->limit(3)
            ->get();

        $relatedProperties = Property::active()
            ->where('id', '!=', $property->id)
            ->where('location', $property->location)
            ->limit(6)
            ->get();

        return view('public.property', compact('property', 'similarProperties', 'relatedProperties'));
    }

    public function showService(Service $service): View
    {
        if (!$service->is_active || $service->archived_at) {
            abort(404);
        }

        $this->trackPageView($service);

        $similarServices = Service::active()
            ->where('id', '!=', $service->id)
            ->where('user_id', $service->user_id)
            ->limit(3)
            ->get();

        $relatedServices = Service::active()
            ->where('id', '!=', $service->id)
            ->where('service_type', $service->service_type)
            ->limit(6)
            ->get();

        return view('public.service', compact('service', 'similarServices', 'relatedServices'));
    }

    public function trackLead(Request $request, string $type, int $id): JsonResponse
    {
        $request->validate([
            'contact_method' => 'required|in:phone,email,external',
        ]);

        $leadable = null;
        if ($type === 'property') {
            $leadable = Property::active()->findOrFail($id);
        } elseif ($type === 'service') {
            $leadable = Service::active()->findOrFail($id);
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        $rate = $leadable->user->getRatePerLead();

        $lead = Lead::create([
            'user_id' => $leadable->user_id,
            'leadable_type' => get_class($leadable),
            'leadable_id' => $leadable->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->headers->get('referer'),
            'session_data' => [
                'contact_method' => $request->contact_method,
                'timestamp' => now()->toISOString(),
            ],
            'rate_charged' => $rate,
        ]);

        $leadable->increment('lead_count');

        return response()->json([
            'success' => true,
            'message' => 'Lead tracked successfully',
            'lead_id' => $lead->id,
        ]);
    }

    private function trackPageView($viewable): void
    {
        PageView::track(
            $viewable,
            request()->ip(),
            request()->userAgent(),
            request()->headers->get('referer'),
            [
                'timestamp' => now()->toISOString(),
                'url' => request()->url(),
            ]
        );
    }
}