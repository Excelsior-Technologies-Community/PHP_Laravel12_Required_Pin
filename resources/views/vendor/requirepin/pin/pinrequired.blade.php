@extends('requirepin::layouts.app')

@section('content')
<<<<<<< HEAD
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">
    <div class="w-full max-w-md px-4">
        <div class="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-5 text-white text-center">
                <h2 class="text-xl font-bold tracking-wide">🔐 Secure Access</h2>
                <p class="text-sm opacity-80">Enter your PIN to continue</p>
            </div>

            <div class="p-8">
                @php
                    $msg = "Please enter your 6-digit PIN.";
                    $formAction = "#";

                    if (session('pin_validation')) {
                        $data = json_decode(session('pin_validation'), true);
                        if (is_array($data)) {
                            $msg = $data['message'] ?? $msg;
                            $formAction = $data['action'] ?? "#";
                        }
                    }

                    if (session('return_payload')) {
                        $payload = json_decode(session('return_payload'), true);
                        if (is_array($payload)) {
                            $msg = $payload['message'] ?? 'Invalid PIN';
                        }
                    }
                @endphp

                {{-- Message --}}
                <div class="mb-6 p-3 rounded-xl bg-blue-50 text-blue-700 text-sm text-center border border-blue-100">
                    {{ $msg }}
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ $formAction }}">
                    @csrf

                    <div class="mb-6">
                        <label for="_pin" class="block text-sm font-semibold text-gray-700 mb-3 text-center">
                            Enter 6-Digit PIN
                        </label>

                        <input 
                            id="_pin" 
                            type="password" 
                            name="_pin"
                            maxlength="6"
                            inputmode="numeric"
                            required 
                            autofocus
                            placeholder="••••••"
                            
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            
                            class="w-full border-2 border-gray-200 p-4 rounded-2xl text-center text-3xl tracking-[0.8rem] 
                            focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all
                            @error('_pin') border-red-500 focus:ring-red-200 @enderror"
                        >

                        @error('_pin')
                            <p class="text-red-500 text-xs mt-2 text-center font-semibold">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 
                        text-white font-bold py-3 rounded-2xl shadow-lg transition duration-200 transform hover:scale-[1.02]"
                    >
                        Verify & Unlock
                    </button>
                </form>
            </div>
=======

<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-blue-100">

    <div class="w-full max-w-md px-4">

        <div class="bg-white shadow-2xl rounded-3xl p-8">

            <h2 class="text-center text-xl font-bold mb-2">
                🔐 Secure Access
            </h2>

            <p class="text-center text-gray-500 mb-6">
                Enter your 6-digit PIN
            </p>

            <form method="POST" action="{{ route('pin.verify') }}">
                @csrf

                <input
                    type="password"
                    name="_pin"
                    maxlength="6"
                    inputmode="numeric"
                    required
                    autofocus
                    class="w-full border p-3 text-center text-2xl rounded-xl focus:ring-2 focus:ring-blue-500"
                    oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                >

                @error('_pin')
                    <p class="text-red-500 text-sm text-center mt-2">
                        {{ $message }}
                    </p>
                @enderror

                @if(auth()->user()->pin_attempts>0)

                <p class="text-orange-500 text-sm text-center mt-2">
                    Remaining attempts:
                    {{ 3-auth()->user()->pin_attempts }}
                </p>

                @endif

                @if(auth()->user()->pin_locked_until && now()->lt(auth()->user()->pin_locked_until))

                <div class="bg-red-100 text-red-600 p-3 rounded-lg mt-3 text-center text-sm">
                    Account temporarily locked.<br>
                    Try again after:
                    {{ auth()->user()->pin_locked_until }}
                </div>

                @endif

                <button class="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl transition">
                    Verify & Unlock
                </button>

            </form>

>>>>>>> main
        </div>

    </div>

</div>

@endsection