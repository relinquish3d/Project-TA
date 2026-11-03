
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Game - PlayAll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 p-6 text-gray-900">


<main class="mx-auto max-w-5xl">
    <a href="{{ route('admin.dashboard') }}"
       class="text-sm text-gray-500 hover:text-black">
        ← Kembali ke Dashboard
    </a>


    <div class="mb-6 mt-4">
        <h1 class="text-2xl font-bold">Kategori Game</h1>
        <p class="mt-1 text-sm text-gray-500">
            Kelola kategori game yang tersedia di PlayAll.
        </p>
    </div>


    @if(session('success'))
        <div class="mb-4 rounded-lg bg-white border p-4">
            {{ session('success') }}
        </div>
    @endif


    <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">
        <div class="border-b border-gray-200 p-4 font-semibold">
            Daftar Kategori
        </div>


        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="p-4">No.</th>
                        <th class="p-4">Nama Kategori</th>
                        <th class="p-4">Tanggal Dibuat</th>
                    </tr>
                </thead>
                   
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr>
                            <td class="p-4">{{ $loop->iteration }}</td>
                            <td class="p-4 font-medium">
                                {{ $category->name }}
                            </td>
                            <td class="p-4">
                                {{ $category->created_at?->format('d M Y') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3"
                                class="p-6 text-center text-gray-500">
                                Belum ada kategori game.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</main>


</body>
</html>




