<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsertaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usertasks', function (Blueprint $table) {
            $table->increments('id')->comment('人员出勤编号');
            $table->integer('task_id')->nullable(false)->comment('任务编号');
            $table->integer('user_id')->nullable(false)->comment('人员编号');
            $table->integer('account_id')->default(0)->comment('结算编号');
            $table->dateTime('start_time')->nullable()->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->string('post', 20)->nullable()->comment('岗位');
            $table->decimal('work_hours', 8, 2)->default(1.0)->comment('任务工时');
            $table->decimal('work_salary', 8, 2)->default(0)->comment('岗位工资');
            $table->decimal('extra_hours', 8, 2)->default(0)->comment('加班工时');
            $table->decimal('extra_salary', 8, 2)->default(0)->comment('加班工资');
            $table->decimal('award_salary', 8, 2)->default(0)->comment('奖惩工资');
            $table->decimal('score', 8, 2)->default(6)->comment('任务评分');
            $table->string('remark')->nullable()->comment('出勤备注');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usertasks');
    }
}
