@extends('requirepin::layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-blue-600 p-4 text-white text-center font-bold text-lg">
                🔐 {{ __('Pin Required') }}
            </div>

            <div class="p-8">
                {{-- Error Message Handle --}}
                @php
                    $msg = "Please enter your 4-digit PIN.";
                    $formAction = "#";

                    if (session('pin_validation')) {
                        $data = json_decode(session('pin_validation'), true);
                        if(is_array($data)) {
                            $msg = is_array($data) ? ($data['message'] ?? $msg) : $data;
                            $formAction = $data ?? "#";
                        }
                    }

                    if (session('return_payload')) {
                        $payload = json_decode(session('return_payload'), true);
                        if(is_array($payload)) {
                            $msg = is_array($payload) ? ($payload['message'] ?? 'Invalid PIN') : $payload;
                        }
                    }
                @endphp

                <div class="mb-6 p-3 bg-blue-50 border-l-4 border-blue-500 text-blue-700 text-sm text-center italic">
                    {{ (string) $msg }}
                </div>

                <form method="POST" action="{{ (string) $formAction }}">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="_pin" class="block text-sm font-semibold text-gray-700 mb-2 text-center">Enter PIN</label>
                        <input id="_pin" type="password" name="_pin" 
                            class="w-full border-2 border-gray-200 p-3 rounded-xl text-center text-3xl tracking-[1rem] focus:border-blue-500 focus:ring-0 transition-all @error('_pin') border-red-500 @enderror"
                            required autofocus maxlength="4" pattern="[0-9]{4}" inputmode="numeric">

                        @error('_pin')
                            <p class="text-red-500 text-xs mt-2 text-center font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition duration-200 transform hover:scale-[1.02]">
                        Verify & Unlock
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection