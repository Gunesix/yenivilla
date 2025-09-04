<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'general' => Cache::get('settings.general', [
                'site_name' => config('app.name', 'Villa Rental'),
                'site_description' => 'Premium villa rental platform',
                'contact_email' => 'info@villakiralama.com',
                'contact_phone' => '+90 555 123 4567',
                'address' => 'Istanbul, Turkey',
                'currency' => 'TRY',
                'timezone' => config('app.timezone', 'Europe/Istanbul'),
                'logo' => '',
            ]),
            'booking' => Cache::get('settings.booking', [
                'min_booking_days' => 1,
                'max_booking_days' => 365,
                'advance_booking_days' => 365,
                'cancellation_policy' => 'flexible',
                'auto_approve_bookings' => false,
                'require_payment_confirmation' => true,
            ]),
            'payment' => Cache::get('settings.payment', [
                'commission_rate' => 10.0,
                'payment_methods' => ['credit_card', 'bank_transfer', 'paypal'],
                'currency_symbol' => '₺',
                'tax_rate' => 18.0,
                'refund_policy' => 'Within 24 hours of booking',
            ]),
            'email' => Cache::get('settings.email', [
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_username' => config('mail.mailers.smtp.username'),
                'smtp_encryption' => config('mail.mailers.smtp.encryption'),
                'from_email' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ]),
        ];
        
        return Inertia::render('Admin/Settings/Index', [
            'settings' => $settings,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function update(Request $request)
    {
        $category = $request->input('category');
        $settings = $request->input('settings', []);
        
        // Merge settings data into request for validation
        $request->merge($settings);
        
        switch ($category) {
            case 'general':
                return $this->updateGeneral($request);
            case 'booking':
                return $this->updateBooking($request);
            case 'payment':
                return $this->updatePayment($request);
            case 'email':
                return $this->updateEmail($request);
            default:
                return back()->withErrors(['error' => 'Invalid settings category.']);
        }
    }
    
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'currency' => 'required|string|max:3',
            'timezone' => 'required|string',
        ]);
        
        // In a real application, you would save these to a settings table or config file
        Cache::put('settings.general', $request->all(), now()->addDays(30));
        
        return back()->with('success', 'General settings updated successfully.');
    }
    
    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
            'category' => 'required|string'
        ]);
        
        try {
            // Eski logoyu sil
            $currentSettings = Cache::get('settings.general', []);
            if (isset($currentSettings['logo']) && $currentSettings['logo']) {
                $oldLogoPath = str_replace('/storage/', '', $currentSettings['logo']);
                Storage::disk('public')->delete($oldLogoPath);
            }
            
            // Yeni logoyu kaydet
            $logoPath = $request->file('logo')->store('logos', 'public');
            $logoUrl = '/storage/' . $logoPath;
            
            // Cache'i güncelle
            $currentSettings['logo'] = $logoUrl;
            Cache::put('settings.general', $currentSettings, now()->addDays(30));
            
            // Güncellenmiş ayarları döndür
            $settings = [
                'general' => Cache::get('settings.general', []),
                'booking' => Cache::get('settings.booking', []),
                'payment' => Cache::get('settings.payment', []),
                'email' => Cache::get('settings.email', []),
            ];
            
            return back()->with([
                'success' => 'Logo başarıyla yüklendi.',
                'settings' => $settings
            ]);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Logo yüklenirken bir hata oluştu: ' . $e->getMessage()]);
        }
    }
    
    public function updateBooking(Request $request)
    {
        $request->validate([
            'min_booking_days' => 'required|integer|min:1',
            'max_booking_days' => 'required|integer|min:1',
            'advance_booking_days' => 'required|integer|min:1',
            'cancellation_policy' => 'required|in:flexible,moderate,strict',
            'auto_approve_bookings' => 'boolean',
            'require_payment_confirmation' => 'boolean',
        ]);
        
        Cache::put('settings.booking', $request->all(), now()->addDays(30));
        
        return back()->with('success', 'Booking settings updated successfully.');
    }
    
    public function updatePayment(Request $request)
    {
        $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'payment_methods' => 'required|string|max:255',
            'currency_symbol' => 'required|string|max:5',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'refund_policy' => 'required|string|max:1000',
        ]);
        
        Cache::put('settings.payment', $request->all(), now()->addDays(30));
        
        return back()->with('success', 'Payment settings updated successfully.');
    }
    
    public function updateEmail(Request $request)
    {
        $request->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|integer',
            'smtp_username' => 'required|string',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'required|in:tls,ssl',
            'from_email' => 'required|email',
            'from_name' => 'required|string',
        ]);
        
        // In a real application, you would update the .env file or config
        Cache::put('settings.email', $request->except('smtp_password'), now()->addDays(30));
        
        return back()->with('success', 'Email settings updated successfully.');
    }
    
    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);
        
        try {
            // Send test email
            \Illuminate\Support\Facades\Mail::raw(
                'This is a test email from your villa rental admin panel.',
                function ($message) use ($request) {
                    $message->to($request->test_email)
                           ->subject('Test Email - Villa Rental Admin');
                }
            );
            
            return back()->with('success', 'Test email sent successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }
}