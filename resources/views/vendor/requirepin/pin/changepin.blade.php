@extends('requirepin::layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-full max-w-md">

        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">

            <div class="bg-green-600 p-4 text-white text-center font-bold text-lg">
                🔑 Change PIN
            </div>

            <div class="p-8">

                {{-- MESSAGE --}}
                @if(session('return_payload'))
                    @php
                        [$status, $code, $data] = json_decode(session('return_payload'), true);
                        $msg = $data['message'] ?? 'Success';
                    @endphp

                    <div class="mb-4 p-3 text-center rounded-lg
                        {{ $status === 'fail' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                        {{ $msg }}
                    </div>
                @endif

                <form method="POST" action="{{ route('changePinWeb') }}">
                    @csrf

                    <input type="password" name="current_pin" placeholder="Old PIN"
                        class="w-full p-3 border rounded-lg mb-3" required>

                    <input type="password" name="pin" placeholder="New PIN"
                        class="w-full p-3 border rounded-lg mb-3" required>

                    <input type="password" name="pin_confirmation" placeholder="Confirm PIN"
                        class="w-full p-3 border rounded-lg mb-3" required>

                    <button class="w-full bg-green-600 text-white p-3 rounded-lg">
                        Update PIN
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection