<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {

            // ID（主キー）
            $table->id();

            // TODOの内容（文字列、最大255文字）
            $table->string('title');

            // 完了状態（ブール値： true/false、デフォルトは未完了=false）
            $table->boolean('is_completed')->default(false);

            // 作成日時と更新日時
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todos');
    }
};
