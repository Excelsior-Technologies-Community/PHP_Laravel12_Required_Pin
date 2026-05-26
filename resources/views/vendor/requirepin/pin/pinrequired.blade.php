@extends('requirepin::layouts.app')

@section('content')

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

        </div>

    </div>

</div>

@endsection