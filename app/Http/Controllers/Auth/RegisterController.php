<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function generateUniqueReferralCode()
    {
        do {
            // Generate a code with MILES prefix and 6 random numbers
            $code = 'MILES' . str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'wallet_address' => ['required', 'string'],
            'private_key' => ['required', 'string'],
            'phone' => ['required', 'string', 'unique:users'],
            'referral_code' => ['nullable', 'string', 'exists:users,referral_code'],
        ]);

        try {
            DB::beginTransaction();

            // Find referrer if referral code was provided
            $referredBy = null;
            if ($request->referral_code) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                if ($referrer) {
                    $referredBy = $referrer->id;
                }
            }

            // Generate unique referral code
            $referralCode = $this->generateUniqueReferralCode();

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'referral_code' => $referralCode,
                'referred_by' => $referredBy,
            ]);

            // Create wallet
            $wallet = new Wallet([
                'address' => $request->wallet_address,
                'private_key' => encrypt($request->private_key),
                'balance' => 0
            ]);

            $user->wallet()->save($wallet);

            DB::commit();
            Auth::login($user);

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Account created successfully!',
                    'redirect' => route('dashboard')
                ]);
            }

            return redirect('dashboard')->with('success', 'Account created successfully! Your TRON wallet has been generated.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to create account: ' . $e->getMessage()
                ], 500);
            }

            return back()->withInput()->withErrors([
                'error' => 'Failed to create account: ' . $e->getMessage()
            ]);
        }
    }
}
