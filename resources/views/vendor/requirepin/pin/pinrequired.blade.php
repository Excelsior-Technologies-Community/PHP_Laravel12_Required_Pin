@extends('requirepin::layouts.app')

@section('content')
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
        </div>
    </div>
</div>
@endsection