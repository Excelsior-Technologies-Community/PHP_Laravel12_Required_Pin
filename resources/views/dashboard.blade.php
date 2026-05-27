@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gradient-to-br from-black via-gray-900 to-black py-10">

    <div class="max-w-7xl mx-auto px-6">

        {{-- HEADER --}}
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-white flex items-center gap-3">
                📊 Login Activity Dashboard
            </h1>

            <p class="text-gray-400 mt-2 text-lg">
                View your recent login activity and device history.
            </p>
        </div>


        {{-- SEARCH BOX --}}
        <div class="bg-gray-900 border border-gray-800 shadow-2xl rounded-3xl p-5 mb-8">

            <form method="GET"
                  action="{{ route('dashboard') }}"
                  class="flex flex-col md:flex-row gap-4">

                <input
                    type="text"
                    name="search"
                    placeholder="Search IP Address..."
                    value="{{ request('search') }}"
                    class="flex-1 bg-black border border-gray-700 text-white rounded-2xl px-5 py-4
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none
                    placeholder-gray-500 transition"
                >

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 rounded-2xl
                    shadow-lg transition duration-300 hover:scale-105"
                >
                    🔍 Search
                </button>

            </form>
        </div>


        {{-- TABLE CARD --}}
        <div class="bg-gray-900 border border-gray-800 rounded-3xl shadow-2xl overflow-hidden">

            {{-- TABLE HEADER --}}
            <div class="px-6 py-5 border-b border-gray-800 bg-black">
                <h2 class="text-2xl font-bold text-white">
                    🔐 Login Records
                </h2>
            </div>


            {{-- TABLE --}}
            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gray-800">

                        <tr>

                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                IP Address
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                Device / Browser
                            </th>

                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-300 uppercase tracking-wider">
                                Login Time
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-800">

                        @forelse($logs as $log)

                            <tr class="hover:bg-gray-800 transition duration-200">

                                {{-- IP --}}
                                <td class="px-6 py-5 text-white font-medium">
                                    {{ $log->ip_address }}
                                </td>

                                {{-- DEVICE --}}
                                <td class="px-6 py-5 text-gray-300 text-sm">
                                    {{ $log->user_agent }}
                                </td>

                                {{-- TIME --}}
                                <td class="px-6 py-5 text-blue-400 font-semibold">
                                    {{ $log->login_at }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="3"
                                    class="px-6 py-10 text-center text-gray-500 text-lg">

                                    🚫 No login activity found.

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>


        {{-- PAGINATION --}}
        <div class="mt-8">

            <div class="bg-gray-900 border border-gray-800 rounded-2xl p-4 shadow-lg">
                {{ $logs->links() }}
            </div>

        </div>

    </div>

</div>

@endsection