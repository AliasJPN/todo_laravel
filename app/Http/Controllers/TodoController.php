<?php

namespace App\Http\Controllers;

use App\Models\Todo; // Todoモデルを使えるようにする
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // 1. TODO一覧の表示
    public function index()
    {
        // データベースからすべてのTODOを作成日順に取得
        $todos = Todo::orderBy('created_at', 'desc')->get();

        // 'todos.index'という名前のビューに、取得したデータを渡して表示
        return view('todos.index', compact('todos'));
    }

    // 2. 新しいTODOの保存
    public function store(Request $request)
    {
        // 入力値のチェック（バリデーション）
        $request->validate([
            'title' => 'required|max:255', // タイトルは必須、最大255文字
        ]);

        // データベースに保存
        Todo::create([
            'title' => $request->title,
        ]);

        // 一覧画面にリダイレクト（戻る）
        return redirect()->route('todos.index');
    }

    // 3. 完了状態の更新
    public function update(Todo $todo)
    {
        // 現在の完了状態を反転させる (trueならfalse、falseならtrue)
        $todo->update([
            'is_completed' => !$todo->is_completed,
        ]);

        return redirect()->route('todos.index');
    }

    // 4. TODOの削除
    public function destroy(Todo $todo)
    {
        // データを削除
        $todo->delete();

        return redirect()->route('todos.index');
    }
}
