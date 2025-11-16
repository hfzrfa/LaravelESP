<x-filament::page>
    @php($data = $this->getData())

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h2 class="text-lg font-bold">Temperature (Latest)</h2>
            <p class="text-3xl font-semibold mt-2">{{ $data['latest_temp'] }} °C</p>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h2 class="text-lg font-bold">Humidity (Latest)</h2>
            <p class="text-3xl font-semibold mt-2">{{ $data['latest_hum'] }} %</p>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h2 class="text-lg font-bold">Total Records</h2>
            <p class="text-3xl font-semibold mt-2">{{ $data['count_data'] }}</p>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h2 class="text-lg font-bold">Temperature Stats</h2>
            <p>Average: {{ number_format($data['avg_temp'], 2) }} °C</p>
            <p>Max: {{ $data['max_temp'] }} °C</p>
            <p>Min: {{ $data['min_temp'] }} °C</p>
        </div>

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <h2 class="text-lg font-bold">Humidity Stats</h2>
            <p>Average: {{ number_format($data['avg_hum'], 2) }} %</p>
        </div>

    </div>

</x-filament::page>
