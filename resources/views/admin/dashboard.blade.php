<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Visits</div>
                    <div class="text-3xl font-bold mt-2 text-blue-600">{{ $totalVisits }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Users</div>
                    <div class="text-3xl font-bold mt-2 text-green-600">{{ $usersCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-medium uppercase">Server Status</div>
                    <div class="text-3xl font-bold mt-2 text-emerald-500">Online</div>
                </div>
            </div>

            <!-- Chart -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Visits (Last 7 Days)</h3>
                <div class="relative h-64">
                    <!-- Simple CSS Bar Chart for MVP -->
                    <div class="flex items-end justify-between h-full gap-2">
                        @foreach($chartData as $index => $value)
                            <div class="w-full flex flex-col items-center group">
                                <div class="w-full bg-blue-100 dark:bg-blue-900 rounded-t-sm relative transition-all hover:bg-blue-200 dark:hover:bg-blue-800" style="height: {{ $value > 0 ? max(($value / (max($chartData) ?: 1)) * 100, 5) : 1 }}%">
                                    <div class="absolute -top-6 left-1/2 -translate-x-1/2 bg-black text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $value }}
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-gray-500">{{ $chartLabels[$index] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Top Downloads Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- All Time Top -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Top Downloads (All Time)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Video Title</th>
                                    <th scope="col" class="px-6 py-3 text-right">Downloads</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDownloadsAllTime as $title => $count)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]" title="{{ $title }}">
                                            {{ $title }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{ $count }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center">No downloads yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 7 Days Top -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Top Downloads (Last 7 Days)</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Video Title</th>
                                    <th scope="col" class="px-6 py-3 text-right">Downloads</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topDownloads7Days as $title => $count)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap overflow-hidden text-ellipsis max-w-[200px]" title="{{ $title }}">
                                            {{ $title }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{ $count }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center">No downloads this week.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                 <h3 class="text-lg font-bold mb-4 text-gray-700 dark:text-gray-300">Quick Actions</h3>
                 <div class="flex gap-4">
                     <a href="/telescope" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                        Open Telescope
                     </a>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>
