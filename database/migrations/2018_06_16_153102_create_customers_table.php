<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id')->comment('客户编号');
            $table->string('name', 20)->unique()->comment('客户名称');
            $table->string('from', 20)->nullable()->comment('客户来源');
            $table->string('linkman',20)->nullable()->comment('联系人');
            $table->string('phone', 20)->nullable()->comment('联系电话');
            $table->string('email', 50)->nullable()->comment('电子邮件');
            $table->string('card_type',20)->nullable()->comment('证件类型');
            $table->string('card_number', 30)->nullable()->comment('证件号码');
            $table->string('fax', 20)->nullable()->comment('传真号码');
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
        Schema::dropIfExists('customers');
    }
}
