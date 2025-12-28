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

---
