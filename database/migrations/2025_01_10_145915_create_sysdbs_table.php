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
            $table->string('name'); // Nome do banco de dados
            $table->string('type',30);
            $table->string('description')->nullable(); // Descrição opcional
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['emp','name']); // Nome único por empresa
        });

        Schema::create('systables', function (Blueprint $table) {
            $table->char('emp',env('SYS_EMP_SIZE',2))->nullable();
            $table->char('fil',env('SYS_FIL_SIZE',2))->nullable();            
            $table->id();
            $table->foreignId('sysdb_id')->constrained('sysdbs')->onDelete('restrict'); // Relaciona ao banco de dados
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
            $table->unique(['emp','sysdb_id', 'name']); // único por empresa e banco de dados
        });

        Schema::create('syscolumns', function (Blueprint $table) {
            $table->char('emp',env('SYS_EMP_SIZE',2))->nullable();
            $table->char('fil',env('SYS_FIL_SIZE',2))->nullable();
            $table->id();
            $table->foreignId('systable_id')->constrained('systables')->onDelete('restrict');
            $table->string('table')->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('description')->nullable(); // Descrição opcional
            $table->char('type',2)->nullable(false);;
            $table->boolean('required_on_create');
            $table->boolean('required_on_update');
            $table->boolean('required_on_revise');
            $table->boolean('grid')->default(true);
            $table->integer('grid_order');
            $table->integer('grid_width')->default(200);
            $table->enum('grid_align',['left','right','center'])->default('left');
            $table->string('grid_label')->nullable();
            $table->boolean('form_on_create')->default(true);
            $table->boolean('form_on_read')->default(true);
            $table->boolean('form_on_update')->default(true);
            $table->boolean('form_on_revise')->default(true);
            $table->integer('form_order');
            $table->enum('form_align',['left','right','center'])->default('left');
            $table->string('form_label')->nullable();
            $table->string('placeholder')->nullable();
            $table->string('sqlcombo')->nullable();
            // $table->bigInteger('id_systablesearch')->nullable();
            $table->boolean('readonly_on_create')->default(false);
            $table->boolean('readonly_on_update')->default(false);
            $table->boolean('readonly_on_revise')->default(false);
            $table->boolean('filterby');
            $table->boolean('orderby')->default(true);
            $table->boolean('searchby');
            $table->enum('context',['R','V'])->default('R');
            // $table->string('default')->nullable(true);
            $table->string('label_class')->nullable();
            $table->string('field_class')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['emp','systable_id','name']);
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
