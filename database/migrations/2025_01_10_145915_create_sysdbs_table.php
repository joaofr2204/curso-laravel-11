<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sysdbs', function (Blueprint $table) {
            $table->char('emp',env('SYS_EMP_SIZE',2))->nullable();
            $table->char('fil',env('SYS_FIL_SIZE',2))->nullable();            
            $table->id();
            $table->string('name')->unique(); // Nome ou alias da conexao 
            $table->string('type',30);
            $table->string('dbname'); // Nome do banco de dados
            $table->string('description')->nullable(); // Descrição opcional
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['emp','name']); // Nome único por empresa
        });

        Schema::create('systables', function (Blueprint $table) {
            $table->char('emp',env('SYS_EMP_SIZE',2))->nullable();
            $table->char('fil',env('SYS_FIL_SIZE',2))->nullable();            
            $table->id();
            $table->string('db');
            $table->string('name'); // Nome da tabela
            $table->string('description')->nullable(); // Descrição opcional
            $table->enum('empfil_share',['E','S','M'])->default('E');//EXCLUSIVE-SHARE-MIXED
            $table->boolean('create')->default(true);
            $table->boolean('read')->default(true);
            $table->boolean('update')->default(true);
            $table->boolean('delete')->default(true);
            $table->boolean('revise')->default(false);
            $table->boolean('clone')->default(false);
            $table->boolean('export')->default(true);
            $table->boolean('files')->default(false);
            $table->json('formfiles_filter')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['emp','db', 'name']); // único por empresa e banco de dados

            $table->foreign('db')->references('name')->on('sysdbs')->onDelete('restrict');

            $table->index('name'); // Índice simples na coluna 'name'
        });

        Schema::create('syscolumns', function (Blueprint $table) {
            $table->char('emp',env('SYS_EMP_SIZE',2))->nullable();
            $table->char('fil',env('SYS_FIL_SIZE',2))->nullable();
            $table->id();
            $table->string('table');
            $table->string('name');
            $table->string('description')->nullable(); // Descrição opcional
            $table->char('type',2);
            $table->boolean('required_on_create');
            $table->boolean('required_on_edit');
            $table->boolean('required_on_review');
            $table->boolean('grid')->default(true);
            $table->integer('grid_order');
            $table->integer('grid_width')->default(200);
            $table->enum('grid_align',['left','right','center'])->default('left');
            $table->string('grid_label')->nullable();
            $table->boolean('form_on_create')->default(true);
            $table->boolean('form_on_show')->default(true);
            $table->boolean('form_on_edit')->default(true);
            $table->boolean('form_on_review')->default(true);
            $table->integer('form_order');
            $table->enum('form_align',['left','right','center'])->default('left');
            $table->string('form_label')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('sqlcombo')->nullable();
            // $table->bigInteger('id_systablesearch')->nullable();
            $table->boolean('readonly_on_create')->default(false);
            $table->boolean('readonly_on_edit')->default(false);
            $table->boolean('readonly_on_review')->default(false);
            $table->boolean('filterby');
            $table->boolean('orderby')->default(true);
            $table->boolean('searchby');
            $table->enum('context',['R','V'])->default('R');
            // $table->string('default')->nullable(true);
            $table->string('label_class')->nullable();
            $table->string('field_class')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['emp','table','name']);

            $table->foreign('table')->references('name')->on('systables')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sysdbs');
        Schema::dropIfExists('systables');
        Schema::dropIfExists('syscolumns');
    }
};
