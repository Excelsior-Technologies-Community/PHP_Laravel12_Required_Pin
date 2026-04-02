@extends('requirepin::layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
            <div class="bg-green-600 p-4 text-white text-center font-bold text-lg">
                🔑 {{ __('Change PIN') }}
            </div>

            <div class="p-8">
                {{-- Status Message --}}
                @if(session('return_payload'))
                    @php
                        [$status, $code, $data] = json_decode(session('return_payload'), true);
                        $msg = is_array($data) ? ($data['message'] ?? 'Success') : $data;
                    @endphp
                    <div class="mb-6 p-3 rounded-lg text-center text-sm font-bold {{ $status === 'fail' ? 'bg-red-50 text-red-600 border border-red-200' : 'bg-green-50 text-green-600 border border-green-200' }}">
                        {{ (string) $msg }}
                    </div>
                @endif

                <form method="POST" action="{{ route('changePinWeb') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Old PIN</label>
                        <input type="password" name="current_pin" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New PIN</label>
                        <input type="password" name="pin" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm PIN</label>
                        <input type="password" name="pin_confirmation" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition" required>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow-md transition transform hover:scale-[1.01]">
                        Update PIN
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection