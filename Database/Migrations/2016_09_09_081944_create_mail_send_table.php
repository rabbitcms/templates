<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailSendTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'mail_send',
            function (Blueprint $table) {
                $table->increments('id');
                $table->morphs('recipient');
                $table->unsignedInteger('template_id');
                $table->string('subject');
                $table->text('html')->nullable();
                $table->text('plain')->nullable();
                $table->text('callback')->nullable();
                $table->boolean('sent');
                $table->timestamps();

                $table->foreign('template_id')
                    ->references('id')->on('mail_templates')
                    ->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('mail_send');
    }
}
