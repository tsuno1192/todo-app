<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TasksImport;

class TaskImportController extends Controller
{
    public function store(Request $request)
    {
        // バリデーション（エクセルファイル形式か）
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // インポート処理の実行
        Excel::import(new TasksImport, $request->file('excel_file'));

        return redirect()->back()->with('success', 'Excel工程表のインポートが完了しました。');
    }
}