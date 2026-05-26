<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\OldPin;

class PinController extends Controller
{
    // Show PIN page
    public function show()
    {
        return view('vendor.requirepin.pin.pinrequired');
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFY PIN
    |--------------------------------------------------------------------------
    */

    public function verify(Request $request)
    {
        $request->validate([
            '_pin' => 'required|digits:6'
        ]);

        $user = Auth::user();

        /*
        Check lock status
        */

        if (
            $user->pin_locked_until &&
            now()->lt($user->pin_locked_until)
        ) {
            return back()->withErrors([
                '_pin' => 'Too many attempts. Try again later'
            ]);
        }

        $enteredPin = $request->_pin;

        $defaultPin = config('requirepin.default', '123456');

        $valid = false;

        /*
        Allow default pin only before changing
        */

        if (
            $user &&
            $user->default_pin == 1 &&
            $enteredPin === $defaultPin
        ) {
            $valid = true;
        }

        /*
        Check saved DB pin
        */

        if (
            !$valid &&
            $user &&
            $user->pin &&
            Hash::check($enteredPin, $user->pin)
        ) {
            $valid = true;
        }

        /*
Wrong PIN logic
*/

        if (!$valid) {

            $user->pin_attempts = $user->pin_attempts + 1;

            if ($user->pin_attempts >= 3) {

                $user->pin_attempts = 0;

                $user->pin_locked_until = now()->addMinutes(2);

                $user->save();

                return back()->withErrors([
                    '_pin' => 'Too many wrong attempts. Locked for 2 minutes'
                ]);
            }

            $user->save();

            return back()->withErrors([
                '_pin' => 'Invalid PIN'
            ]);
        }

        /*
Success reset attempts
*/

        $user->pin_attempts = 0;

        $user->pin_locked_until = null;

        $user->save();

        session([
            'pin_verified' => true
        ]);

        return redirect()->route('dashboard');
    }


    /*
    |--------------------------------------------------------------------------
    | CHANGE PIN
    |--------------------------------------------------------------------------
    */

    public function changePin(Request $request)
    {
        $request->validate([
            'current_pin' => 'required|digits:6',
            'pin' => 'required|digits:6|confirmed',
        ]);

        $user = Auth::user();

        $defaultPin = config('requirepin.default', '123456');

        $isValid = false;

        /*
        Check default PIN
        */

        if (
            $user->default_pin == 1 &&
            $request->current_pin === $defaultPin
        ) {
            $isValid = true;
        }

        /*
        Check DB PIN
        */

        if (
            !$isValid &&
            $user->pin &&
            Hash::check($request->current_pin, $user->pin)
        ) {
            $isValid = true;
        }

        if (!$isValid) {

            return back()->with(
                'return_payload',
                json_encode([
                    'fail',
                    400,
                    [
                        'message' => 'Current PIN is incorrect'
                    ]
                ])
            );
        }

        /*
        Store old pin history
        */

        if ($user->pin) {

            OldPin::create([
                'user_id' => $user->id,
                'pin' => $user->pin
            ]);
        }

        /*
        Save new pin
        */

        $user->update([
            'pin' => Hash::make($request->pin),
            'default_pin' => 0
        ]);

        return back()->with(
            'return_payload',
            json_encode([
                'success',
                200,
                [
                    'message' => 'PIN updated successfully'
                ]
            ])
        );
    }
}
