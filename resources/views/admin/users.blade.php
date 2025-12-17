<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl leading-tight text-[#5A9CB5]">
            {{ __('Users Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-[#FACE68]/20 border border-[#FAAC68] text-[#FAAC68] rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div
                class="bg-white overflow-hidden shadow-[0_10px_30px_rgba(90,156,181,0.1)] rounded-[32px] border border-gray-100">
                <div class="p-8">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-[#5A9CB5]">Users Management</h3>
                        <p class="text-gray-600 mt-1">Total Users: {{ $users->count() }}</p>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('admin.users') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <!-- Search -->
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search by name, email, phone"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A9CB5] focus:border-transparent outline-none">
                            </div>

                            <!-- Role Filter -->
                            <div>
                                <select name="role"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A9CB5] focus:border-transparent outline-none">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher
                                    </option>
                                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student
                                    </option>
                                    <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent
                                    </option>
                                </select>
                            </div>

                            <!-- Gender Filter -->
                            <div>
                                <select name="gender"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A9CB5] focus:border-transparent outline-none">
                                    <option value="">All Genders</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male
                                    </option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <select name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5A9CB5] focus:border-transparent outline-none">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="flex gap-2">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 bg-[#5A9CB5] text-white rounded-lg hover:bg-[#4A8CA5] transition-all font-bold shadow-md">
                                    Filter
                                </button>
                                <a href="{{ route('admin.users') }}"
                                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all text-center font-bold">
                                    Clear
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="overflow-x-auto rounded-xl border border-gray-100">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#FACE68]">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Phone</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Gender</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div
                                                        class="h-10 w-10 rounded-full flex items-center justify-center text-white font-bold bg-[#5A9CB5]">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $user->phone_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @php
                                                    $roles = [];
                                                    if ($user->role == 'admin') {
                                                        $roles[] = [
                                                            'name' => 'Admin',
                                                            'class' => 'bg-[#FA6868]/10 text-[#FA6868]',
                                                        ];
                                                    }
                                                    if ($user->teacher) {
                                                        $roles[] = [
                                                            'name' => 'Teacher',
                                                            'class' => 'bg-[#5A9CB5]/10 text-[#5A9CB5]',
                                                        ];
                                                    }
                                                    if ($user->student) {
                                                        $roles[] = [
                                                            'name' => 'Student',
                                                            'class' => 'bg-green-100 text-green-800',
                                                        ];
                                                    }
                                                    if ($user->parentModel) {
                                                        $roles[] = [
                                                            'name' => 'Parent',
                                                            'class' => 'bg-purple-100 text-purple-800',
                                                        ];
                                                    }
                                                @endphp
                                                @foreach ($roles as $role)
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $role['class'] }}">
                                                        {{ $role['name'] }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ ucfirst($user->gender) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($user->role == 'student' && $user->student)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $user->student->account_status == 'active' ? 'bg-[#5A9CB5]/10 text-[#5A9CB5]' : 'bg-[#FA6868]/10 text-[#FA6868]' }}">
                                                    {{ ucfirst($user->student->account_status) }}
                                                </span>
                                            @elseif ($user->role == 'teacher' && $user->teacher)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $user->teacher->account_status == 'active' ? 'bg-[#5A9CB5]/10 text-[#5A9CB5]' : 'bg-[#FA6868]/10 text-[#FA6868]' }}">
                                                    {{ ucfirst($user->teacher->account_status) }}
                                                </span>
                                            @elseif ($user->role == 'parent' && $user->parentModel)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            {{ $user->parentModel->account_status == 'active' ? 'bg-[#5A9CB5]/10 text-[#5A9CB5]' : 'bg-[#FA6868]/10 text-[#FA6868]' }}">
                                                    {{ ucfirst($user->parentModel->account_status) }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (auth()->check() && auth()->id() === $user->id)
                                                <span class="text-gray-400 text-xs">-</span>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#5A9CB5] text-white hover:bg-[#4A8CA5] transition-all shadow-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                                        class="inline-block"
                                                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#FA6868] text-white hover:bg-[#E05050] transition-all shadow-sm">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>