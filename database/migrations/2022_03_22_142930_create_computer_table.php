<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComputerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('computers', function (Blueprint $table) {
            $table->increments('id')->comment("Asset ID");
            $table->string('name', 384)
                ->unique('name')
                ->comment("Computer Name");
            $table->string('migration_status', 385)
                ->nullable()
                ->index('lu__migration_status')
                ->comment("Migration Status");
            $table->string('user_name', 385)
                ->nullable()
                ->comment("Description");
            $table->string('location', 385)
                ->nullable()
                ->index('lu__location')
                ->comment("Location");
            $table->string('computer_type', 385)
                ->nullable()
                ->index('lu__computer_type')
                ->comment("Computer Type");
            $table->string('computer_model', 385)
                ->nullable()
                ->index('lu__computer_model')
                ->comment("Computer Model");
            $table->string('operating_system', 385)
                ->nullable()
                ->index('lu__operating_system')
                ->comment("Operating System");
            $table->string('windows_10_version', 385)
                ->nullable()
                ->index('lu__windows_10_version')
                ->comment("Windows 10 Version");
            $table->string('memory_gb', 385)
                ->nullable()
                ->index('lu__memory_gb')
                ->comment("Memory (GB)");
            $table->string('disk_size_gb', 385)
                ->nullable()
                ->index('lu__disk_size_gb')
                ->comment("Disk Size (GB)");
            $table->string('free_space_gb', 256)
                ->nullable()
                ->comment("Free Space (GB)");
            $table->string('serial', 256)
                ->nullable()
                ->comment("Serial Number");
            $table->string('business_unit', 385)
                ->nullable()
                ->index('lu__business_unit')
                ->comment("Business Unit");
            $table->string('department', 385)
                ->nullable()
                ->index('lu__department')
                ->comment("Department");
            $table->enum('replacement_ordered', ['Yes', 'No', 'Not-Needed'])
                ->nullable()
                ->index('replacement_ordered')
                ->comment("HW Replacement Ordered");
            $table->string('static_ip', 256)
                ->nullable()
                ->comment("Static IP");
            $table->enum('state', ['in stock', 'in use'])
                ->nullable()
                ->index('state')
                ->comment("State");
            $table->string('central_build_site', 385)
                ->nullable()
                ->index('lu__central_build_site')
                ->comment("Central Build Site");
            $table->string('last_logon_user', 256)
                ->nullable()
                ->comment("Last Logon User");
            $table->enum('vetted', ['NO', 'YES'])
                ->nullable()
                ->comment("Asset Vetted");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computers');
    }
}
