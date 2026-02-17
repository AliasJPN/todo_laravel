<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>Laravel TODOアプリ</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        .todo-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .completed {
            text-decoration: line-through;
            color: gray;
        }

        form {
            display: inline;
        }
    </style>
</head>

<body>
    <h1>📋 TODOリスト</h1>

    <form action="{{ route('todos.store') }}" method="POST">
        @csrf
        <input type="text" name="title" placeholder="新しいTODOを入力" required>
        <button type="submit">追加</button>
    </form>

    <hr>

    <ul>
        @foreach ($todos as $todo)
            <li class="todo-item">
                <form action="{{ route('todos.update', $todo) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">
                        {{ $todo->is_completed ? '✅' : '⬜' }}
                    </button>
                </form>

                <span class="{{ $todo->is_completed ? 'completed' : '' }}">
                    {{ $todo->title }}
                </span>

                <form action="{{ route('todos.destroy', $todo) }}" method="POST" style="margin-left: 10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('削除しますか？')">削除</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>

</html>
