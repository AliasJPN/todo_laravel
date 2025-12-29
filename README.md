## 環境構築

### 🔰 ステップ 0: 事前準備（Docker のインストール）

Laravel Sail を使うためには、まず**Docker Desktop**が PC にインストールされ、起動している必要があります。

-   **Windows の場合:** WSL 2 と Docker Desktop の組み合わせが必要です。
-   **macOS の場合:** Docker Desktop for Mac をインストールしてください。

Docker が起動していることを確認してください。

### 🛠️ ステップ 1: プロジェクトの作成（Sail 推奨コマンド）

Laravel Sail では、`curl`コマンドを使ってプロジェクトを作成するのが最も推奨され、手軽な方法です。このコマンドを実行すると、Laravel プロジェクトのインストールと、Sail 関連の設定がすべて同時に行われます。

ターミナルを開き、プロジェクトを作成したいディレクトリに移動してから、以下のコマンドを実行します。

```bash
# {プロジェクト名}の部分は好きな名前に置き換えてください
curl -s "https://laravel.build/laravel-sail-app" | bash
```

**✅ コマンドの説明:**

-   `curl -s "..."`: Laravel のビルドスクリプトをダウンロードします。
-   `| bash`: ダウンロードしたスクリプトをシェル（bash）で実行します。
-   `laravel-sail-app`: 今回作成するプロジェクトのフォルダ名です。

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

-   `./vendor/bin/sail`: Laravel Sail の実行ファイルです。
-   `up`: `docker compose up` と同じ意味で、環境を構築しコンテナを起動します。

コンテナの初回起動時には、必要なコンテナイメージがダウンロード・ビルドされるため、時間がかかります。

#### 💡 便利な Sail コマンド（エイリアス設定）

毎回 `./vendor/bin/sail` と入力するのは面倒なので、ターミナルの設定ファイル（例: `~/.bashrc` や `~/.zshrc`）に以下のエイリアスを設定すると便利です。

```bash
alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'
```

この設定を行えば、**`sail up -d`** や **`sail artisan migrate`** のように、よりシンプルにコマンドを実行できるようになります。

### 🌍 ステップ 3: アプリケーションの動作確認

コンテナが起動したら、ブラウザでアプリケーションにアクセスします。

-   **URL:** **http://localhost**
-   `localhost` でアクセスすると、Laravel の初期画面（Welcome ページ）が表示されるはずです。

#### 起動しない場合

-   コンテナ削除: `docker-compose down -v`
-   コンテナ起動: `docker-compose up -d`
-   キャッシュクリア: `sail artisan config:clear`
-   マイグレーション: `sail artisan migrate`

### 💾 ステップ 4: データベースの初期設定

Sail で作成したプロジェクトは、最初から MySQL コンテナが立ち上がっています。`.env`ファイルには既に MySQL 接続情報が記述されているため、以下のコマンドでマイグレーション（テーブル作成）を実行するだけで OK です。

1. **Sail 経由でマイグレーションを実行**

```bash
./vendor/bin/sail artisan migrate
```

**✅ コマンドの説明:**

-   `./vendor/bin/sail artisan ...`: Sail の環境（Docker コンテナ内）で、Laravel の Artisan コマンドを実行するための書式です。

これで、開発環境の立ち上げと、基本的なデータベースの準備は完了です！

## データベース設計

※要件定義はハンズオン形式のため省略。

TODO アプリケーションの中心となるのは、TODO アイテムを保存するデータベースです。Laravel では、データベースのスキーマ（構造）をバージョン管理するために「**マイグレーション (Migration)**」という機能を使用します。

### 📝 ステップ 1: TODO テーブルのマイグレーションファイルの作成

Artisan コマンドを使って、`todos` テーブルを作成するためのマイグレーションファイルを生成します。

Sail 環境で以下のコマンドを実行してください。

```bash
sail artisan make:migration create_todos_table
```

#### 💡 コマンドの構文解説

-   `sail artisan`: **Sail 環境**（Docker コンテナ内）で Artisan（Laravel の CLI ツール）を実行するためのコマンドです。
-   `make:migration`: **マイグレーションファイル**を生成するための Artisan コマンドです。
-   `create_todos_table`: 生成するマイグレーションファイル名です。Laravel では慣例として、`create_` + **テーブル名（複数形）** + `_table` という形式で命名します。これにより、Laravel が自動的にファイル内の基本的な構造（テーブル名など）を設定してくれます。

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

-   `Blueprint`: テーブルの構造を定義するクラスです。`$table`変数として渡され、カラムの型や制約を定義するメソッド（例: `string()`, `id()`）を提供します。
-   `Schema`: データベースのスキーマ操作（テーブル作成、削除など）を行うためのファサード（クラスのインターフェース）です。

2. **`public function up(): void`**

-   マイグレーションを**実行**する際に呼び出されるメソッドです。
-   `Schema::create('todos', ...)`: `todos`という名前の新しいテーブルを作成します。
-   `$table->id();`: **符号なし BIGINT**の**オートインクリメント**な主キー（Primary Key）として`id`カラムを作成します。これは、レコードを一意に識別するために必須です。
-   `$table->string('title');`: **VARCHAR (255 文字)** 型のカラム`title`を作成します。TODO の内容を保存します。
-   `$table->boolean('is_completed')->default(false);`: **BOOLEAN** 型のカラム`is_completed`を作成します。TODO の完了状態（`true`で完了、`false`で未完了）を保持します。
-   `->default(false)`: レコードが新しく作成されたとき、このカラムに何も値を指定しなかった場合の**デフォルト値**として`false`を設定しています。

-   `$table->timestamps();`: 慣習としてよく使われる 2 つのタイムスタンプカラム（`created_at`と`updated_at`）を一括で作成します。これらは Laravel が自動で管理します。

3. **`public function down(): void`**

-   マイグレーションを**ロールバック**（取り消し）する際に呼び出されるメソッドです。
-   `Schema::dropIfExists('todos');`: `todos`テーブルが**存在する場合に**そのテーブルを削除します。

### 📝 ステップ 3: マイグレーションの実行

ファイル編集後、以下の Artisan コマンドを実行して、データベースに実際に`todos`テーブルを作成します。

```bash
sail artisan migrate
```

#### 💡 コマンドの構文解説

-   `migrate`: `database/migrations` ディレクトリ内にある、まだ実行されていないマイグレーションファイルを順次実行し、データベースのスキーマを更新する Artisan コマンドです。実行済みのマイグレーションは`migrations`テーブルに記録されます。

コマンドが成功すると、データベース内に`todos`テーブルが作成されます。
