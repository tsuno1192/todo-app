<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            タスク詳細・承認フロー
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="task-approval-section">
                    <!-- 現在のステータス情報 -->
                    <div class="alert alert-info mb-4 p-3 bg-blue-100 text-blue-800 rounded">
                        現在地: <strong>Step {{ $task->current_step }}</strong> / ステータス:
                        <strong>{{ $task->status }}</strong>
                    </div>

                    <!-- 図書リンク表示・入力欄 -->
                    <div class="form-group mb-4">
                        <label for="document_url" class="block font-medium text-sm text-gray-700">成果物 図書URL</label>
                        <input type="url" name="document_url" value="{{ $task->document_url }}"
                            class="form-control mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                            {{ $task->current_step > 1 ? 'readonly' : '' }}>
                    </div>

                    <!-- コメント履歴表示 -->
                    @if ($task->comment)
                        <div class="form-group mb-4">
                            <label class="block font-medium text-sm text-gray-700">直近のコメント・差し戻し理由</label>
                            <p class="form-control-static text-red-600 mt-1">{{ $task->comment }}</p>
                        </div>
                    @endif

                    <!-- 承認者向けアクションフォーム -->
                    @can('approve', $task)
                        <div class="mt-6 border-t pt-4">
                            <form action="{{ route('tasks.stepUp', $task) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="form-group mb-2">
                                    <textarea name="comment" class="form-control w-full rounded-md border-gray-300 shadow-sm" placeholder="承認/コメントを入力（任意）"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary bg-blue-600 text-white px-4 py-2 rounded">STEP
                                    UP（承認・上程）</button>
                            </form>

                            <form action="{{ route('tasks.remand', $task) }}" method="POST">
                                @csrf
                                <div class="form-group mb-2">
                                    <textarea name="comment" class="form-control w-full rounded-md border-gray-300 shadow-sm" placeholder="差し戻し理由を入力（必須）"
                                        required></textarea>
                                </div>
                                <button type="submit"
                                    class="btn btn-danger bg-red-600 text-white px-4 py-2 rounded">差し戻し</button>
                            </form>
                        </div>
                    @endcan
                </div>

            </div>
        </div>
    </div>

    <!-- 承認履歴・ログ一覧セクション -->
    <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">承認・変更履歴（監査証跡）</h3>

        @if ($task->logs->count() > 0)
            <div class="space-y-4">
                @foreach ($task->logs()->latest()->get() as $log)
                    <div class="border-l-4 border-blue-500 pl-4 py-2 bg-gray-50 rounded">
                        <div class="text-sm text-gray-600">
                            <span class="font-bold text-gray-800">{{ $log->user->name ?? '不明なユーザー' }}</span> が
                            <span class="badge bg-blue-100 text-blue-800 px-2 py-0.5 rounded">Step
                                {{ $log->step }}</span> で
                            <span class="font-semibold">{{ $log->action }}</span> を実行
                            <span
                                class="float-right text-xs text-gray-500">{{ $log->created_at->format('Y/m/d H:i') }}</span>
                        </div>
                        @if ($log->comment)
                            <div class="mt-1 text-sm text-gray-700">
                                コメント: {{ $log->comment }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-gray-500">まだ履歴はありません。</p>
        @endif
    </div>
</x-app-layout>
