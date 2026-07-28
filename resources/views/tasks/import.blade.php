<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            タスクの一括インポート
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="card-header font-bold mb-4">Excel一括インポート（承認フロー開始）</div>
                <div class="card-body">
                    <form action="{{ route('tasks.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="excel_file" class="form-label">Excelファイル選択 (.xlsx, .xls)</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success bg-green-600 text-white px-4 py-2 rounded">インポートして承認フローへ乗せる</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>