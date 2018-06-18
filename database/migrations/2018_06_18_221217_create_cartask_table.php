<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartasks', function (Blueprint $table) {
            $table->increments('id')->comment('车辆出勤编号');
            $table->integer('task_id')->nullable(false)->comment('任务编号');
            $table->integer('car_id')->nullable(false)->comment('车辆编号');
            $table->integer('account_id')->default(0)->comment('结算编号');
            $table->dateTime('start_time')->nullable()->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->decimal('rent_cost', 8, 2)->default(0)->comment('车费');
            $table->decimal('oil_cost', 8, 2)->default(0)->comment('油费');
            $table->decimal('toll_cost', 8, 2)->default(0)->comment('路费');
            $table->decimal('park_cost', 8, 2)->default(0)->comment('停车费');
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
        Schema::dropIfExists('cartasks');
    }
}
