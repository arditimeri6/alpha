<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('postcode')->nullable();
            $table->string('place')->nullable();
            $table->string('social_security_number')->nullable();
            $table->string('company')->nullable();
            $table->string('company_address')->nullable();
            $table->string('function_in_the_company')->nullable();
            $table->string('fix_phone_number')->nullable();
            $table->string('mobile_phone_number')->nullable();
            $table->string('bank')->nullable();
            $table->string('account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('cardholder')->nullable();
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
        Schema::dropIfExists('users');
    }
}
