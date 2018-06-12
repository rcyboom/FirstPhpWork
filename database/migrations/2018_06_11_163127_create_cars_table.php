<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //车辆编号、型号、车牌号、状态、联系人、联系电话、租车价格、来源、备注
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id')->comment('车辆编号');
            $table->string('car_type', 20)->nullable(false)->comment('车辆型号');
            $table->string('car_number', 20)->nullable(false)->unique()->comment('车牌号');
            $table->string('state',20)->comment('状态')->nullable();
            $table->string('linkman', 20)->nullable(false)->comment('联系人');
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->integer('work_price')->default(0)->comment('租车价格');
            $table->string('from',20)->comment('来源')->nullable();
            $table->string('remark',100)->comment('备注')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
