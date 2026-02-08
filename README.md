**README.mdは AI が生成したコンテンツを人間が編集したものです。**

## 環境構築

### 🔰 ステップ 0: 事前準備（Docker のインストール）

Laravel Sail を使うためには、まず**Docker Desktop**が PC にインストールされ、起動している必要があります。

- **Windows の場合:** WSL 2 と Docker Desktop の組み合わせが必要です。
- **macOS の場合:** Docker Desktop for Mac をインストールしてください。

Docker が起動していることを確認してください。

### 🛠️ ステップ 1: プロジェクトの作成（Sail 推奨コマンド）

Laravel Sail では、`curl`コマンドを使ってプロジェクトを作成するのが最も推奨され、手軽な方法です。このコマンドを実行すると、Laravel プロジェクトのインストールと、Sail 関連の設定がすべて同時に行われます。

ターミナルを開き、プロジェクトを作成したいディレクトリに移動してから、以下のコマンドを実行します。

```bash
# {プロジェクト名}の部分は好きな名前に置き換えてください
curl -s "https://laravel.build/{プロジェクト名}" | bash
```

**✅ コマンドの説明:**

- `curl -s "..."`: Laravel のビルドスクリプトをダウンロードします。
- `| bash`: ダウンロードしたスクリプトをシェル（bash）で実行します。
- `{プロジェクト名}`: 今回作成するプロジェクトのフォルダ名です。

このコマンドを実行すると、必要な Docker イメージのダウンロードと Laravel プロジェクトのファイルが作成されるため、数分かかることがあります。

### ⚙️ ステップ 2: ディレクトリへの移動と Sail の起動

プロジェクトの作成が完了したら、そのディレクトリに移動し、Laravel Sail を起動します。

1. **プロジェクトディレクトリへ移動**

```bash
cd laravel-sail-app
```

2. **Sail の起動**

```bash
./vendor/bin/sail up
```

または、バックグラウンドで起動する場合は `-d` オプションをつけます。

```bash
./vendor/bin/sail up -d
```

**✅ コマンドの説明:**

- `./vendor/bin/sail`: Laravel Sail の実行ファイルです。
- `up`: `docker compose up` と同じ意味で、環境を構築しコンテナを起動します。

コンテナの初回起動時には、必要なコンテナイメージがダウンロード・ビルドされるため、時間がかかります。

#### 💡 便利な Sail コマンド（エイリアス設定）

毎回 `./vendor/bin/sail` と入力するのは面倒なので、ターミナルの設定ファイル（例: `~/.bashrc` や `~/.zshrc`）に以下のエイリアスを設定すると便利です。

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

この設定を行えば、**`sail up -d`** や **`sail artisan migrate`** のように、よりシンプルにコマンドを実行できるようになります。

### 🌍 ステップ 3: アプリケーションの動作確認

コンテナが起動したら、ブラウザでアプリケーションにアクセスします。

- **URL:** **http://localhost**
- `localhost` でアクセスすると、Laravel の初期画面（Welcome ページ）が表示されるはずです。

#### 起動しない場合

- コンテナ削除: `docker-compose down -v`
- コンテナ起動: `docker-compose up -d`
- キャッシュクリア: `sail artisan config:clear`
- マイグレーション: `sail artisan migrate`

### 💾 ステップ 4: データベースの初期設定

Sail で作成したプロジェクトは、最初から MySQL コンテナが立ち上がっています。`.env`ファイルには既に MySQL 接続情報が記述されているため、以下のコマンドでマイグレーション（テーブル作成）を実行するだけで OK です。

1. **Sail 経由でマイグレーションを実行**

```bash
./vendor/bin/sail artisan migrate
```

**✅ コマンドの説明:**

- `./vendor/bin/sail artisan ...`: Sail の環境（Docker コンテナ内）で、Laravel の Artisan コマンドを実行するための書式です。

これで、開発環境の立ち上げと、基本的なデータベースの準備は完了です！

## データベース設計

※ハンズオンのため、設計の説明は省略します。

TODO アプリケーションの中心となるのは、TODO アイテムを保存するデータベースです。Laravel では、データベースのスキーマ（構造）をバージョン管理するために「**マイグレーション (Migration)**」という機能を使用します。

### 📝 ステップ 1: TODO テーブルのマイグレーションファイルの作成

Artisan コマンドを使って、`todos` テーブルを作成するためのマイグレーションファイルを生成します。

Sail 環境で以下のコマンドを実行してください。

```bash
sail artisan make:migration create_todos_table
```

#### 💡 コマンドの構文解説

- `sail artisan`: **Sail 環境**（Docker コンテナ内）で Artisan（Laravel の CLI ツール）を実行するためのコマンドです。
- `make:migration`: **マイグレーションファイル**を生成するための Artisan コマンドです。
- `create_todos_table`: 生成するマイグレーションファイル名です。Laravel では慣例として、`create_` + **テーブル名（複数形）** + `_table` という形式で命名します。これにより、Laravel が自動的にファイル内の基本的な構造（テーブル名など）を設定してくれます。

コマンドを実行すると、`database/migrations` ディレクトリ内に、以下のようなファイル名の新しいファイルが生成されます（日付と時刻は実行したタイミングによって異なります）。

```
database/migrations/2025_12_29_010000_create_todos_table.php
```

### 📝 ステップ 2: マイグレーションファイルの内容の編集

生成されたファイル（`database/migrations/xxxx_xx_xx_xxxxxx_create_todos_table.php`）を開き、`up()` メソッドを以下のように編集してください。

#### 💻 `database/migrations/xxxx_xx_xx_xxxxxx_create_todos_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションを実行する（テーブルを作成する）
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            // ID (主キー)
            $table->id();

            // TODOの内容 (文字列、最大255文字)
            $table->string('title');

            // 完了状態 (ブール値: true/false、デフォルトは未完了=false)
            $table->boolean('is_completed')->default(false);

            // 作成日時と更新日時
            $table->timestamps();
        });
    }

    /**
     * マイグレーションを元に戻す（ロールバック時、テーブルを削除する）
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
```

#### 💡 構文の詳細解説

1. **`use ...;`**

- `Blueprint`: テーブルの構造を定義するクラスです。`$table`変数として渡され、カラムの型や制約を定義するメソッド（例: `string()`, `id()`）を提供します。
- `Schema`: データベースのスキーマ操作（テーブル作成、削除など）を行うためのファサード（クラスのインターフェース）です。

2. **`public function up(): void`**

- マイグレーションを**実行**する際に呼び出されるメソッドです。
- `Schema::create('todos', ...)`: `todos`という名前の新しいテーブルを作成します。
- `$table->id();`: **符号なし BIGINT**の**オートインクリメント**な主キー（Primary Key）として`id`カラムを作成します。これは、レコードを一意に識別するために必須です。
- `$table->string('title');`: **VARCHAR (255 文字)** 型のカラム`title`を作成します。TODO の内容を保存します。
- `$table->boolean('is_completed')->default(false);`: **BOOLEAN** 型のカラム`is_completed`を作成します。TODO の完了状態（`true`で完了、`false`で未完了）を保持します。
- `->default(false)`: レコードが新しく作成されたとき、このカラムに何も値を指定しなかった場合の**デフォルト値**として`false`を設定しています。

- `$table->timestamps();`: 慣習としてよく使われる 2 つのタイムスタンプカラム（`created_at`と`updated_at`）を一括で作成します。これらは Laravel が自動で管理します。

3. **`public function down(): void`**

- マイグレーションを**ロールバック**（取り消し）する際に呼び出されるメソッドです。
- `Schema::dropIfExists('todos');`: `todos`テーブルが**存在する場合に**そのテーブルを削除します。

### 📝 ステップ 3: マイグレーションの実行

ファイル編集後、以下の Artisan コマンドを実行して、データベースに実際に`todos`テーブルを作成します。

```bash
sail artisan migrate
```

#### 💡 コマンドの構文解説

- `migrate`: `database/migrations` ディレクトリ内にある、まだ実行されていないマイグレーションファイルを順次実行し、データベースのスキーマを更新する Artisan コマンドです。実行済みのマイグレーションは`migrations`テーブルに記録されます。

コマンドが成功すると、データベース内に`todos`テーブルが作成されます。

## モデル設定

Laravel の「**モデル**」は、データベースの特定のテーブル（今回は`todos`テーブル）と対話するための PHP のクラスです。モデルを通じて、データの取得、挿入、更新、削除（CRUD 操作）を行います。

### 📝 ステップ 1: モデルファイルの作成

Artisan コマンドを使用して、`todos`テーブルに対応する`Todo`モデルを作成します。

Sail 環境で以下のコマンドを実行してください。

```bash
sail artisan make:model Todo
```

#### 💡 コマンドの構文解説

- `make:model`: Eloquent モデルクラスを生成するための Artisan コマンドです。
- `Todo`: モデルの名前です。Laravel では慣例として、テーブル名（`todos`：複数形）に対して**モデル名は単数形**（`Todo`）で、**パスカルケース**（大文字始まり）で命名します。

コマンドが成功すると、`app/Models` ディレクトリ内に `Todo.php` というファイルが作成されます。

### 📝 ステップ 2: モデルファイルの内容の編集（可変プロパティの設定）

作成された `app/Models/Todo.php` を開き、データベースへのデータ挿入や更新を可能にするために、**`$fillable` プロパティ**を追加・設定します。

#### 💻 `app/Models/Todo.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    /**
     * 一括割り当て（マスアサインメント）可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'is_completed',
    ];
}
```

#### 💡 構文の詳細解説

1. **`class Todo extends Model`**

- `Todo`クラスは、Laravel のデータベース操作の基盤となる`Illuminate\Database\Eloquent\Model`クラスを継承しています。これにより、データベース操作の強力な機能（Eloquent ORM）が利用可能になります。

2. **`protected $fillable = [...]`**

- `$fillable` は、**マスアサインメント（一括割り当て）**から保護するガード機能（セキュリティ機能）に関連するプロパティです。
- **マスアサインメント**とは、ユーザーからの入力データ配列（例: フォームの送信データ）を一度にモデルに流し込んでレコードを作成・更新する操作です。
- この機能は便利ですが、悪意のあるユーザーが意図しないカラム（例: ユーザー権限や作成日）を勝手に書き換えるセキュリティリスク（**マスアサインメント脆弱性**）があります。
- `$fillable` プロパティにカラム名（`'title'`, `'is_completed'`）をリストアップすることで、「**これらのカラムだけは一括で安全に割り当て可能ですよ**」と Laravel に宣言しています。

### 📝 ステップ 3: モデルとテーブル名の紐付け（確認）

モデル名が単数形（`Todo`）、テーブル名が複数形（`todos`）という Laravel の命名規則に従っているため、Laravel は自動的にこのモデルが`todos`テーブルと紐づいていることを認識します。

もし命名規則に従わないテーブル名を使う場合は、以下のようにモデル内に `$table` プロパティを設定する必要がありますが、**今回は設定不要です**。

```php
// 今回は不要ですが、参考として
// protected $table = 'my_todo_list';
```

## ルーティング (Routing) の設定

Laravelでは、すべてのWebリクエストは `routes/web.php` というファイルで定義されます。ここで「このURLに来たら、このコントローラのこのメソッドを動かす」というルールを書きます。

### 📝 ステップ 1: ルート定義の追加

`routes/web.php` を開き、既存のコードの下に以下のコードを追加してください。

#### 💻 `routes/web.php`

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController; // 後で作成するコントローラをインポート

// TODO一覧表示画面
Route::get('/', [TodoController::class, 'index'])->name('todos.index');

// TODO登録処理
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');

// TODO完了状態の更新処理（更新なのでPATCH/PUTを使用）
Route::patch('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');

// TODO削除処理
Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
```

#### 💡 構文の詳細解説

1. **`use App\Http\Controllers\TodoController;`**
    - これから作成する `TodoController` クラスをこのファイル内で使えるように宣言しています。

2. **`Route::get('/', ...)`**
    - **HTTPメソッド** (`get`, `post`, `patch`, `delete`): 通信の種類を指定します。
        - `get`: データの取得（画面表示）
        - `post`: データの新規作成
        - `patch`: データの一部更新
        - `delete`: データの削除

    - **URLパス** (`'/'`, `'/todos'`): ブラウザで入力するアドレスです。

    - **`[TodoController::class, 'index']`**:
        - 第一要素: 実行するコントローラ名
        - 第二要素: そのコントローラ内にある実行したいメソッド名（関数名）

3. **`->name('todos.index')`**
    - **ルート名**: このルートに名前を付けます。ビュー（HTML）の中でリンクを作る際、URLを直接書くのではなく `route('todos.index')` のように名前で呼び出せるようになり、URL変更に強い設計になります。

4. **`{todo}` (ルートパラメータ)**
    - `{todo}` の部分は、更新や削除したいTODOのIDがここに入ることを示します（例: `/todos/1`）。LaravelはこのIDを自動的に読み取って、特定のデータを操作できるようにしてくれます。
