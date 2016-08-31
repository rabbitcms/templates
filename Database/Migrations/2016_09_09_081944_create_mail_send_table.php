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
                $table->text('html');
                $table->text('plain');
                $table->text('callback');
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
