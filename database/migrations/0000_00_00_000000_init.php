<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Init extends Migration
{
    private const PROJECTS_TABLE = 'projects';
    private const TASK_PRIORITIES_TABLE = 'task_priorities';
    private const TASKS_TABLE = 'tasks';

    /**
     * @return void
     */
    public function up(): void
    {
        Schema::create(self::PROJECTS_TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create(self::TASK_PRIORITIES_TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('priority');
        });

        Schema::create(self::TASKS_TABLE, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('priority_id');
            $table->unsignedInteger('project_id');
            $table->timestamps();

            $table->foreign('priority_id')
                ->references('id')
                ->on(self::TASK_PRIORITIES_TABLE);

            $table->foreign('project_id')
                ->references('id')
                ->on(self::PROJECTS_TABLE)
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });

        DB::table(self::TASK_PRIORITIES_TABLE)->insert([
            ['name' => 'Low', 'priority' => 10],
            ['name' => 'Medium', 'priority' => 20],
            ['name' => 'High', 'priority' => 30],
        ]);
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(self::TASKS_TABLE);
        Schema::dropIfExists(self::TASK_PRIORITIES_TABLE);
        Schema::dropIfExists(self::PROJECTS_TABLE);
    }
}
