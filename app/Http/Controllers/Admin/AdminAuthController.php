<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\AdminUser;

class AdminAuthController extends Controller
{
    /**
     * Admin login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = AdminUser::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'roles' => $admin->getRoleNames(),
                'permissions' => $admin->getAllPermissions()->pluck('name'),
            ],
        ]);
    }

    /**
     * Admin logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated admin user
     */
    public function me(Request $request)
    {
        $admin = $request->user();
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'roles' => $admin->getRoleNames(),
                'permissions' => $admin->getAllPermissions()->pluck('name'),
            ],
        ]);
    }
}
