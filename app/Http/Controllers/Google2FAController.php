<?php

namespace App\Http\Controllers;

use PragmaRX\Google2FA\Google2FA;
use Illuminate\Http\Request;

class Google2FAController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    public function setup()
    {
        try {
            $user = auth()->user();

            // Generate a new secret key
            $secretKey = $this->google2fa->generateSecretKey();

            // Store it in session temporarily
            session(['2fa_temp_secret' => $secretKey]);

            // Generate the QR code URL
            $qrCodeUrl = $this->google2fa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $secretKey
            );

            // Generate QR code as image using external service (with fallback)
            $qrCodeImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrCodeUrl);

            return response()->json([
                'success' => true,
                'qrCodeUrl' => $qrCodeUrl,
                'qrCodeImageUrl' => $qrCodeImageUrl,
                'secret' => $secretKey
            ]);

        } catch (\Exception $e) {
            // Silent 2FA setup error
            return response()->json([
                'success' => false,
                'message' => 'Failed to setup 2FA'
            ], 500);
        }
    }

    public function verify(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|size:6'
            ]);

            $user = auth()->user();
            $secret = session('2fa_temp_secret');

            if (!$secret) {
                return response()->json([
                    'success' => false,
                    'message' => '2FA setup session expired. Please try again.'
                ], 400);
            }

            // Clean the code
            $code = preg_replace('/\s+/', '', $request->code);

            // Verify the code
            $valid = $this->google2fa->verifyKey($secret, $code);

            if ($valid) {
                // Save the secret to the user's record
                $user->google2fa_secret = $secret;
                $user->google2fa_enabled = true;
                $user->save();

                // Clear the temporary secret
                session()->forget('2fa_temp_secret');

                return response()->json([
                    'success' => true,
                    'message' => '2FA enabled successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid authentication code'
            ], 400);

        } catch (\Exception $e) {
            // Silent 2FA verification error
            return response()->json([
                'success' => false,
                'message' => 'Verification failed'
            ], 500);
        }
    }

    public function validateWithdraw(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string|size:6'
            ]);

            $user = auth()->user();

            if (!$user->google2fa_enabled || !$user->google2fa_secret) {
                return response()->json([
                    'success' => false,
                    'message' => '2FA is not properly configured'
                ], 400);
            }

            $code = preg_replace('/\s+/', '', $request->code);
            $valid = $this->google2fa->verifyKey($user->google2fa_secret, $code);

            return response()->json([
                'success' => $valid,
                'message' => $valid ? 'Code verified' : 'Invalid code'
            ]);

        } catch (\Exception $e) {
            // Silent 2FA withdrawal verification error
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify code'
            ], 500);
        }
    }

    public function disable(Request $request)
    {
        try {
            $user = auth()->user();
            $user->google2fa_secret = null;
            $user->google2fa_enabled = false;
            $user->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Silent 2FA disable error
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable 2FA'
            ], 500);
        }
    }
}
