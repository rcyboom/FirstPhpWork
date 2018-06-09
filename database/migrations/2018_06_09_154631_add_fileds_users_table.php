<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiledsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //修改为全局唯一
            $table->string('name', 50)->unique()->change()->comment('姓名');
            //增加以下字段
            $table->string('phone_number', 20)->comment('联系电话')->nullable();
            $table->string('sex',8)->comment('性别')->default('男');
            $table->string('state',20)->comment('状态')->nullable();

            $table->date('birthday')->comment('出生日期')->nullable();
            $table->date('work_time')->comment('入职时间')->nullable();
            $table->string('card_type',16)->comment('证件类型')->nullable();

            $table->string('card_number',25)->comment('证件号码')->nullable();
            $table->string('duty',20)->comment('职务')->nullable();
            $table->string('level',20)->comment('等级')->nullable();

            $table->string('from',20)->comment('来源')->nullable();
            $table->integer('fix_salary')->comment('固定工资')->default(0);
            $table->integer('work_salary')->comment('出班工资')->default(0);

            $table->integer('extra_salary')->comment('加班工资')->default(0);
            $table->string('family_address',100)->comment('家庭住址')->nullable();
            $table->string('personal_address',100)->comment('现住址')->nullable();

            $table->string('remark',100)->comment('备注')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_name_unique');

            $table->dropColumn(
                [
                    'phone_number','sex', 'state',
                    'birthday','work_time','card_type',
                    'card_number','duty','level',
                    'from','fix_salary','work_salary',
                    'extra_salary','family_address','personal_address',
                    'remark'
                ]);
        });
    }
}
