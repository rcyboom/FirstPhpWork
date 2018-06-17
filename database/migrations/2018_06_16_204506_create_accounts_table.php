<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id')->comment('财务编号');
            $table->dateTime('account_time')->nullable(false)->comment('时间');
            $table->string('account_type', 20)->nullable(false)->comment('收支类型');
            $table->string('account_object', 20)->nullable(false)->comment('收支对象');
            $table->string('handler', 20)->nullable(false)->comment('经办人');
            $table->string('object_name',20)->nullable()->comment('对象名称');
            $table->decimal('money', 8, 2)->default(0)->comment('金额');
            $table->string('trade_type')->nullable()->comment('交易类型');
            $table->string('trade_account')->nullable()->comment('交易账户');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('accounts');
    }
}
