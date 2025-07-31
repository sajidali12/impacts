<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Property;
use App\Models\Service;
use App\Models\Lead;
use App\Models\Invoice;
use App\Models\Setting;
use App\Models\HomepageBanner;
use App\Jobs\GenerateInvoicesJob;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::whereIn('role', ['developer', 'service_provider'])->count(),
            'active_users' => User::whereIn('role', ['developer', 'service_provider'])->where('is_active', true)->count(),
            'total_properties' => Property::count(),
            'active_properties' => Property::active()->count(),
            'total_services' => Service::count(),
            'active_services' => Service::active()->count(),
            'total_leads_this_month' => Lead::thisMonth()->count(),
            'total_revenue_this_month' => Invoice::thisMonth()->paid()->sum('total_amount'),
            'pending_invoices' => Invoice::pending()->count(),
            'overdue_invoices' => Invoice::overdue()->count(),
        ];

        $recentLeads = Lead::with(['user', 'leadable'])
            ->latest()
            ->limit(10)
            ->get();

        $recentInvoices = Invoice::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $topPerformers = User::whereIn('role', ['developer', 'service_provider'])
            ->withCount(['leads' => function ($query) {
                $query->thisMonth();
            }])
            ->orderBy('leads_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentLeads', 'recentInvoices', 'topPerformers'));
    }

    public function users(Request $request): View
    {
        $query = User::whereIn('role', ['developer', 'service_provider']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $users = $query->withCount(['properties', 'services', 'leads'])
            ->latest()
            ->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function properties(Request $request): View
    {
        $query = Property::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'archived') {
                $query->whereNotNull('archived_at');
            }
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(20);
        $developers = User::where('role', 'developer')->select('id', 'name')->get();

        return view('admin.properties', compact('properties', 'developers'));
    }

    public function services(Request $request): View
    {
        $query = Service::with('user');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'archived') {
                $query->whereNotNull('archived_at');
            }
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->latest()->paginate(20);
        $serviceProviders = User::where('role', 'service_provider')->select('id', 'name')->get();

        return view('admin.services', compact('services', 'serviceProviders'));
    }

    public function leads(Request $request): View
    {
        $query = Lead::with(['user', 'leadable', 'invoice']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('type')) {
            $query->where('leadable_type', $request->type === 'property' ? Property::class : Service::class);
        }

        if ($request->filled('invoiced')) {
            $query->where('is_invoiced', $request->invoiced === 'yes');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->latest()->paginate(20);
        $users = User::whereIn('role', ['developer', 'service_provider'])->select('id', 'name')->get();

        $stats = [
            'total_leads' => Lead::count(),
            'this_month' => Lead::thisMonth()->count(),
            'last_month' => Lead::lastMonth()->count(),
            'uninvoiced' => Lead::uninvoiced()->count(),
            'total_revenue' => Lead::invoiced()->sum('rate_charged'),
        ];

        return view('admin.leads', compact('leads', 'users', 'stats'));
    }

    public function invoices(Request $request): View
    {
        $query = Invoice::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        $invoices = $query->latest()->paginate(20);
        $users = User::whereIn('role', ['developer', 'service_provider'])->select('id', 'name')->get();

        $stats = [
            'total_invoices' => Invoice::count(),
            'pending' => Invoice::pending()->count(),
            'paid' => Invoice::paid()->count(),
            'overdue' => Invoice::overdue()->count(),
            'total_amount_pending' => Invoice::pending()->sum('total_amount'),
            'total_amount_paid' => Invoice::paid()->sum('total_amount'),
        ];

        return view('admin.invoices', compact('invoices', 'users', 'stats'));
    }

    public function generateInvoices(): RedirectResponse
    {
        GenerateInvoicesJob::dispatch();

        return redirect()
            ->route('admin.invoices')
            ->with('success', 'Invoice generation job has been queued. Invoices will be generated shortly.');
    }

    public function settings(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'default_rate_per_lead' => 'required|numeric|min:0',
            'invoice_due_days' => 'required|integer|min:1|max:90',
            'deactivation_grace_days' => 'required|integer|min:1|max:30',
            'archive_after_weeks' => 'required|integer|min:1|max:12',
            'company_name' => 'required|string|max:255',
            'company_email' => 'required|email|max:255',
            'company_address' => 'nullable|string|max:500',
            'vat_number' => 'nullable|string|max:50',
            'enable_email_notifications' => 'boolean',
            'enable_weekly_reports' => 'boolean',
        ]);

        $settingsData = [
            'default_rate_per_lead' => ['value' => $request->default_rate_per_lead, 'type' => 'decimal'],
            'invoice_due_days' => ['value' => $request->invoice_due_days, 'type' => 'integer'],
            'deactivation_grace_days' => ['value' => $request->deactivation_grace_days, 'type' => 'integer'],
            'archive_after_weeks' => ['value' => $request->archive_after_weeks, 'type' => 'integer'],
            'company_name' => ['value' => $request->company_name, 'type' => 'string'],
            'company_email' => ['value' => $request->company_email, 'type' => 'string'],
            'company_address' => ['value' => $request->company_address, 'type' => 'string'],
            'vat_number' => ['value' => $request->vat_number, 'type' => 'string'],
            'enable_email_notifications' => ['value' => $request->boolean('enable_email_notifications'), 'type' => 'boolean'],
            'enable_weekly_reports' => ['value' => $request->boolean('enable_weekly_reports'), 'type' => 'boolean'],
        ];

        foreach ($settingsData as $key => $data) {
            Setting::set($key, $data['value'], $data['type']);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    public function toggleUserStatus(User $user): RedirectResponse
    {
        if (in_array($user->role, ['admin', 'marketing'])) {
            return redirect()
                ->back()
                ->with('error', 'Cannot toggle status for admin users.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        
        return redirect()
            ->back()
            ->with('success', "User {$user->name} has been {$status}.");
    }

    public function siteSettings(): View
    {
        $settings = HomepageBanner::getActive();
        
        return view('admin.site-settings', compact('settings'));
    }

    public function updateSiteSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'required|string|max:500',
            'description' => 'nullable|string|max:1000',
            'background_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
            'cta_text' => 'required|string|max:100',
            'cta_link' => 'required|string|max:255',
            'secondary_cta_text' => 'required|string|max:100',
            'secondary_cta_link' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $settings = HomepageBanner::getActive();
        
        if (!$settings) {
            $settings = new HomepageBanner();
        }

        // Handle background image upload
        if ($request->hasFile('background_image')) {
            // Delete old image if exists
            if ($settings->background_image && Storage::disk('public')->exists($settings->background_image)) {
                Storage::disk('public')->delete($settings->background_image);
            }
            
            $path = $request->file('background_image')->store('homepage', 'public');
            $validated['background_image'] = $path;
        }

        // Handle site logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo if exists
            if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
                Storage::disk('public')->delete($settings->site_logo);
            }
            
            $path = $request->file('site_logo')->store('logos', 'public');
            $validated['site_logo'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        
        $settings->fill($validated)->save();

        return redirect()
            ->route('admin.site-settings')
            ->with('success', 'Site settings updated successfully.');
    }
}