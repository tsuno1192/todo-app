<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TasksImport;

class TaskImportController extends Controller
{
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            // インポート実行
            Excel::import(new TasksImport, $request->file('excel_file'));

            return redirect()->route('tasks.index')
                .with('success', 'Excelからタスクをインポートし、多段階承認フローを開始しました。');
                
        } catch (\Exception $e) {
            return redirect()->back()
                .with('error', 'インポートに失敗しました: ' . $e->getMessage());
        }
    }
}