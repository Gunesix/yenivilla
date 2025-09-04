<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }
        
        $users = $query->withCount('bookings')
            ->latest()
            ->paginate(15)
            ->withQueryString();
            
        $stats = [
            'total_users' => User::count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'users_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];
        
        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'stats' => $stats,
            'filters' => $request->only(['search', 'status']),
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function create()
    {
        return Inertia::render('Admin/Users/Create', [
            'breadcrumbs' => [
                ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                ['name' => 'Kullanıcılar', 'url' => route('admin.users.index')],
                ['name' => 'Yeni Kullanıcı', 'url' => null]
            ],
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'email_verified_at' => now(), // Admin created users are automatically verified
        ]);
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Kullanıcı başarıyla oluşturuldu.');
    }
    
    public function show(User $user)
    {
        $user->load([
            'bookings' => function ($query) {
                $query->with('villa')->latest()->take(10);
            }
        ]);
        
        $userStats = [
            'total_bookings' => $user->bookings()->count(),
            'completed_bookings' => $user->bookings()->where('status', 'completed')->count(),
            'cancelled_bookings' => $user->bookings()->where('status', 'cancelled')->count(),
            'total_spent' => $user->bookings()->where('status', 'completed')->sum('total_amount'),
        ];
        
        return Inertia::render('Admin/Users/Show', [
            'user' => $user,
            'userStats' => $userStats,
            'auth' => [
                'user' => \Illuminate\Support\Facades\Auth::guard('admin')->user()
            ]
        ]);
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
        
        $user->update($request->only(['name', 'email']));
        
        return back()->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        // Check if user has active bookings
        if ($user->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return back()->withErrors(['error' => 'Cannot delete user with active bookings.']);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}