@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Gestion de l'Expérience Utilisateur</h1>

        <!-- Configuration des points -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Configuration des Points</h2>
            <form action="{{ route('admin.experience.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-medium mb-2">Points par action</h3>
                        @foreach($config['points'] as $key => $value)
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $key)) }}
                            </label>
                            <input type="number" name="points[{{ $key }}]" value="{{ $value }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <h3 class="font-medium mb-2">Niveaux requis</h3>
                        @foreach($config['levels'] as $key => $value)
                        <div class="mb-2">
                            <label class="block text-sm font-medium text-gray-700">
                                {{ ucfirst($key) }}
                            </label>
                            <input type="number" name="levels[{{ $key }}]" value="{{ $value }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Mettre à jour la configuration
                </button>
            </form>
        </div>

        <!-- Liste des utilisateurs -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Points des Utilisateurs</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilisateur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Points
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Niveau
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $user->profile_photo_url }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $user->points->current_points ?? 0 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ $user->getLevel() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.experience.update-user', $user) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="number" name="points" value="{{ $user->points->current_points ?? 0 }}"
                                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900">
                                        Mettre à jour
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 