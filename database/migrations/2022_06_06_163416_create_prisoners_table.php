<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prisoners', function (Blueprint $table) {
            $table->id();
            $table->string('name_and_father_name')->nullable();
            $table->string('arabic_name')->nullable();
            $table->string('iqama_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('detention_authority')->nullable();
            $table->string('region')->nullable();
            $table->string('detention_place')->nullable();
            $table->string('detention_city')->nullable();
            $table->string('prison')->nullable();
            $table->string('gender')->nullable();
            $table->string('cnic')->nullable();
            $table->string('hijri_detention_date')->nullable();
            $table->date('gregorian_detention_date')->nullable();
            $table->string('case_details')->nullable();
            $table->string('sentence_in_years')->nullable();
            $table->string('sentence_in_months')->nullable();
            $table->string('financial_claim')->nullable();
            $table->string('penalty_fine')->nullable();
            $table->string('case_court_name')->nullable();
            $table->string('case_city')->nullable();
            $table->string('case_number')->nullable();
            $table->string('case_prisoner_number')->nullable();
            $table->string('case_claim_number')->nullable();
            $table->string('case_sadad_number')->nullable();
            $table->string('case_claimer_name')->nullable();
            $table->string('case_claimer_contact_number')->nullable();
            $table->date('case_consular_access_date')->nullable();
            $table->date('etd_issuance_date')->nullable();
            $table->string('etd_number')->nullable();
            $table->string('case_closed')->nullable();
            $table->string('case_closing_reason')->nullable();
            $table->string('case_closing_date_hijri')->nullable();
            $table->date('case_closing_date_gg')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('provinces')->nullable();
            $table->string('district')->nullable();
            $table->string('tehseel')->nullable();
            $table->string('muhallah_town')->nullable();
            $table->string('contact_no_in_pakistan')->nullable();
            $table->date('detention_period')->nullable();
            $table->date('expected_release_date')->nullable();
            $table->enum('status',['Detainee','Undertrial','Sentenced','Death Sentenced','Released'])->nullable();
            $table->string('photo')->nullable();
            $table->string('passport')->nullable();
            $table->string('iqama')->nullable();
            $table->string('other')->nullable();
            $table->string('attachment')->nullable();
            $table->string('all_charges')->nullable();
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
        Schema::dropIfExists('prisoners');
    }
};
