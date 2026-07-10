<x-filament-panels::page>
    {{-- Özet kartlar --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-sm text-gray-500">Bugün</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($todayVisitors, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">ziyaretçi</span></p>
            <p class="text-sm text-gray-500">{{ number_format($todayClicks, 0, ',', '.') }} tıklama</p>
        </div>
        <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-sm text-gray-500">Son 7 Gün</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($weekVisitors, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">ziyaretçi</span></p>
            <p class="text-sm text-gray-500">{{ number_format($weekClicks, 0, ',', '.') }} tıklama</p>
        </div>
        <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 p-5">
            <p class="text-sm text-gray-500">Toplam</p>
            <p class="text-2xl font-bold mt-1">{{ number_format($totalVisitors, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">ziyaretçi</span></p>
            <p class="text-sm text-gray-500">{{ number_format($totalClicks, 0, ',', '.') }} tıklama</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Günlük tablo --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden">
            <p class="font-semibold px-5 py-4 border-b border-gray-200 dark:border-gray-800">Son 14 Gün</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-2.5 font-medium">Tarih</th>
                        <th class="px-5 py-2.5 font-medium text-right">Tekil Ziyaretçi</th>
                        <th class="px-5 py-2.5 font-medium text-right">Tıklama</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daily as $row)
                        <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <td class="px-5 py-2.5">{{ $row->date->format('d.m.Y') }}</td>
                            <td class="px-5 py-2.5 text-right font-medium">{{ number_format($row->visitors, 0, ',', '.') }}</td>
                            <td class="px-5 py-2.5 text-right text-gray-500">{{ number_format($row->clicks, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Henüz ziyaret kaydı yok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- En çok gezilen sayfalar --}}
        <div class="rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden">
            <p class="font-semibold px-5 py-4 border-b border-gray-200 dark:border-gray-800">En Çok Gezilen Sayfalar (30 gün)</p>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-200 dark:border-gray-800">
                        <th class="px-5 py-2.5 font-medium">Sayfa</th>
                        <th class="px-5 py-2.5 font-medium text-right">Ziyaretçi</th>
                        <th class="px-5 py-2.5 font-medium text-right">Tıklama</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPages as $row)
                        <tr class="border-b border-gray-100 dark:border-gray-800 last:border-0">
                            <td class="px-5 py-2.5 font-mono text-xs">{{ \Illuminate\Support\Str::limit($row->path, 40) }}</td>
                            <td class="px-5 py-2.5 text-right font-medium">{{ number_format($row->visitors, 0, ',', '.') }}</td>
                            <td class="px-5 py-2.5 text-right text-gray-500">{{ number_format($row->clicks, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400">Henüz ziyaret kaydı yok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
