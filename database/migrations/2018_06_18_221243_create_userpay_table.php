<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserpayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userpays', function (Blueprint $table) {
            $table->increments('id')->comment('奖惩编号');
            $table->integer('user_id')->nullable(false)->comment('人员编号');
            $table->integer('account_id')->default(0)->comment('结算编号');
            $table->dateTime('time')->nullable(false)->comment('奖惩时间');
            $table->string('type',20)->nullable(false)->comment('奖惩类型');
            $table->decimal('money', 8, 2)->default(0)->comment('奖惩金额');
            $table->decimal('score', 8, 2)->default(0)->comment('奖惩评分');
            $table->string('reason')->nullable()->comment('奖惩原因');
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
        Schema::dropIfExists('userpays');
    }
}
