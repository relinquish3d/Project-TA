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
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <section class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 p-4 flex justify-between items-center">
                <span class="font-semibold text-gray-800">Daftar Admin</span>
                <button onclick="openModal('add')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    + Tambah Admin
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="p-4">Nama</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Tanggal Dibuat</th>
                            <th class="p-4 text-right">Aksi</th>
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
                            <td class="p-4 text-right space-x-2">
                                <button onclick="openModal('edit', '{{ $admin->id }}', '{{ $admin->name }}', '{{ $admin->email }}')"
                                    class="text-amber-600 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-md text-xs font-medium transition">
                                    Edit
                                </button>
                                <!-- Menggunakan route admin.users.destroy -->
                                <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus admin ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md text-xs font-medium transition">
        Hapus
    </button>
</form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                Belum ada akun admin.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal Form (Tambah / Edit) -->
    <div id="adminModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-800 mb-4">Tambah Admin</h3>

            <!-- Menggunakan route admin.users.store sebagai default action -->
            <form id="adminForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" id="adminName" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="adminEmail" required
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4" id="passwordGroup">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="adminPassword"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 8 karakter">
                    <small class="text-gray-400 text-xs">Wajib diisi saat tambah, opsional saat edit.</small>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const storeRoute = "{{ route('admin.users.store') }}";

        function openModal(mode, id = null, name = '', email = '') {
            const modal = document.getElementById('adminModal');
            const modalTitle = document.getElementById('modalTitle');
            const adminForm = document.getElementById('adminForm');
            const formMethod = document.getElementById('formMethod');
            const passwordInput = document.getElementById('adminPassword');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (mode === 'add') {
                modalTitle.innerText = 'Tambah Admin';
                adminForm.action = storeRoute;
                formMethod.value = 'POST';
                document.getElementById('adminName').value = '';
                document.getElementById('adminEmail').value = '';
                passwordInput.value = '';
                passwordInput.required = true;
            } else if (mode === 'edit') {
                modalTitle.innerText = 'Edit Admin';
                // Menyesuaikan URL update dengan prefix /admin/users/{id}
                adminForm.action = `/admin/users/${id}`;
                formMethod.value = 'PUT';
                document.getElementById('adminName').value = name;
                document.getElementById('adminEmail').value = email;
                passwordInput.value = '';
                passwordInput.required = false;
            }
        }

        function closeModal() {
            const modal = document.getElementById('adminModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</body>

</html>