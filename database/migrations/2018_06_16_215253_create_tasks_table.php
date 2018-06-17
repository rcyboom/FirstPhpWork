<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id')->comment('任务编号');
            $table->dateTime('check_time')->nullable(false)->default(now())->comment('登记时间');
            $table->integer('customer_id')->nullable(false)->comment('客户编号');
            $table->string('title', 30)->nullable(false)->comment('任务名称');
            $table->string('type', 20)->default('默认类型')->comment('类型');
            $table->string('state', 20)->default('已登记')->comment('状态');
            $table->string('linkman',20)->nullable()->comment('联系人');
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('station', 60)->nullable()->comment('工作地点');
            $table->dateTime('start_time')->nullable()->default(now())->comment('开始时间');
            $table->dateTime('end_time')->nullable()->comment('结束时间');
            $table->decimal('work_hours', 8, 2)->default(1.0)->comment('任务工时');
            $table->decimal('equipment_cost', 8, 2)->default(0)->comment('设备费用');
            $table->decimal('other_cost', 8, 2)->default(0)->comment('其他费用');
            $table->decimal('receivables', 8, 2)->default(0)->comment('结算金额');
            $table->decimal('tax', 8, 2)->default(0)->comment('税费');
            $table->integer('account_id')->default(0)->comment('结算编号');
            $table->string('remark')->nullable()->comment('任务备注');
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
        Schema::dropIfExists('tasks');
    }
}
