<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Admin - PlayAll</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 p-6 text-gray-900">


    <main class="mx-auto max-w-5xl">
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm text-gray-500 hover:text-black">
            ← Kembali ke Dashboard
        </a>


        <div class="mb-6 mt-4">
            <h1 class="text-2xl font-bold">Kelola Admin</h1>
            <p class="mt-1 text-sm text-gray-500">
                Daftar akun administrator PlayAll.
            </p>
        </div>


        @if(session('success'))
        <div class="mb-4 rounded-lg border border-gray-200 bg-white p-4">
            {{ session('success') }}
        </div>
        @endif


        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="border-b border-gray-200 p-4 font-semibold">
                Daftar Admin
            </div>


            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($admins as $admin)
                        <tr>
                            <td class="p-4 font-medium">{{ $admin->name }}</td>
                            <td class="p-4">{{ $admin->email }}</td>
                            <td class="p-4">
                                {{ $admin->created_at?->format('d M Y') ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-gray-500">
                                Belum ada akun admin.
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