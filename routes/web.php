<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskImportController;
use App\Http\Controllers\ApprovalFlowController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    // インポート画面の表示用URL（例: /tasks/import
    Route::get('/tasks/import', [TaskImportController::class, 'showImportForm'])->name('tasks.import.form');
    // アップロード処理のURL（HTMLの form action と合わせる）
    Route::post('/tasks/import', [TaskImportController::class, 'import'])->name('tasks.import');

    // 承認・次段階への上程ルート
    Route::post('/tasks/{task}/step-up', [ApprovalFlowController::class, 'stepUp'])->name('tasks.step-up');
    // 差し戻しルート
    Route::post('/tasks/{task}/remand', [ApprovalFlowController::class, 'remand'])->name('tasks.remand');
});




require __DIR__.'/auth.php';
