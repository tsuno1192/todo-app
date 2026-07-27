<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('タスク詳細・編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" :value="__('タスク名')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="due_date" :value="__('期日')" />
                        <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $task->due_date)" />
                        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="maintenance_system_id" :value="__('保全管理システム紐付けID (maintenance_system_id)')" />
                        <x-text-input id="maintenance_system_id" class="block mt-1 w-full" type="text" name="maintenance_system_id" :value="old('maintenance_system_id', $task->maintenance_system_id)" />
                        <x-input-error :messages="$errors->get('maintenance_system_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('詳細内容')" />
                        <textarea id="description" name="description" rows="4" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $task->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <x-primary-button>
                            {{ __('更新する') }}
                        </x-primary-button>
                        
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            {{ __('戻る') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>