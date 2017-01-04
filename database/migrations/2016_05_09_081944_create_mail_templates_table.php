<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create(
            'mail_templates',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('extends')->nullable();
                $table->char('locale', 10);
                $table->string('subject');
                $table->text('template');
                $table->text('plain');
                $table->boolean('enabled')->default(true);
                $table->timestamps();

                $table->unique(['name', 'locale']);
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('mail_templates');
    }
}
