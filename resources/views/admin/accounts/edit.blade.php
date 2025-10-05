<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Akun') }}
            </h2>
            <a href="{{ route('admin.accounts.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Halaman Akun
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.accounts.update', $account) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nama <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="name"
                                id="name"
                                value="{{ old('name', $account->name) }}"
                                required
                                class="mt-1 block w-full rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIP -->
                        <div>
                            <label for="nip" class="block text-sm font-medium text-gray-700">
                                NIP <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                name="nip"
                                id="nip"
                                value="{{ old('nip', $account->nip) }}"
                                required
                                class="mt-1 block w-full rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('nip') border-red-500 @enderror">
                            @error('nip')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password (Optional for Edit) -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">
                                Password Baru
                            </label>
                            <input type="password"
                                name="password"
                                id="password"
                                class="mt-1 block w-full rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Biarkan kosong untuk mempertahankan kata sandi saat ini</p>
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">
                                Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role"
                                id="role"
                                required
                                class="mt-1 block w-full rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('role') border-red-500 @enderror">
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $account->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="staff" {{ old('role', $account->role) === 'staff' ? 'selected' : '' }}>Staff</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Study Program -->
                        <div>
                            <label for="study_program_id" class="block text-sm font-medium text-gray-700">
                                Program Studi
                            </label>
                            <select name="study_program_id"
                                id="study_program_id"
                                class="mt-1 block w-full rounded-md  shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('study_program_id') border-red-500 @enderror">
                                <option value="">Select Study Program (Optional)</option>
                                @foreach($studyPrograms as $program)
                                    <option value="{{ $program->id }}"
                                        {{ old('study_program_id', $account->study_program_id) == $program->id ? 'selected' : '' }}>
                                        {{ $program->sp_code }} - {{ $program->sp_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('study_program_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <div class="flex items-center">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox"
                                    name="status"
                                    id="status"
                                    value="1"
                                    {{ old('status', $account->status) ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="status" class="ml-2 block text-sm text-gray-700">
                                    Aktif
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Pengguna yang tidak aktif tidak dapat masuk ke sistem</p>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t">
                            <a href="{{ route('admin.accounts.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Batalkan
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Perbarui Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
