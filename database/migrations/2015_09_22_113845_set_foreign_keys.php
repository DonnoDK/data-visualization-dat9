<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetForeignKeys extends Migration
{
    /*
test_data { id, [f]test_person, f[test_case], [f]test_image, valence, timestamp_start, timestamp_end }
eeg_reading { id, [f]channel, [f]test_case, value, timestamp }
gsr_reading { id, [f]test_case, value, timestamp }
fft { id, [f]eeg_reading }
fft_data {id, [f]fft_id, value }
fft_band_data { id, [f]fft_id, [f]fft_band, value, algorithm_type }
    */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('test_case', function($table)
        {
            $table->foreign('test_person_id')->references('id')->on('test_person');
        });
        Schema::table('test_data', function($table)
        {
            $table->foreign('test_person_id')->references('id')->on('test_person');
            $table->foreign('test_case_id')->references('id')->on('test_case');
            $table->foreign('test_image_id')->references('id')->on('test_image');
        });
        Schema::table('eeg_reading', function($table)
        {
            $table->foreign('channel_id')->references('id')->on('eeg_channel');
            $table->foreign('test_case_id')->references('id')->on('test_case');
        });
        
        Schema::table('gsr_reading', function($table)
        {
            $table->foreign('test_case_id')->references('id')->on('test_case');
        });

        Schema::table('fft', function($table)
        {
            $table->foreign('eeg_reading_id')->references('id')->on('eeg_reading');
        });

        Schema::table('fft_data', function($table)
        {
            $table->foreign('fft_id')->references('id')->on('fft');
        });

        Schema::table('fft_band_data', function($table)
        {
            $table->foreign('fft_id')->references('id')->on('fft');
            $table->foreign('band_id')->references('id')->on('fft_band');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('test_case', function($table)
        {
            $table->dropForeign('test_case_test_person_foreign');
        });

        Schema::table('test_data', function($table)
        {
            $table->dropForeign('test_data_test_person_foreign');
            $table->dropForeign('test_data_test_case_foreign');
            $table->dropForeign('test_data_test_image_foreign');
        });

        Schema::table('eeg_reading', function($table)
        {
            $table->dropForeign('eeg_reading_channel_foreign');
            $table->dropForeign('eeg_reading_test_case_foreign');
        });   


        Schema::table('gsr_reading', function($table)
        {
            $table->dropForeign('gsr_reading_test_case_foreign');
        });     

        Schema::table('fft', function($table)
        {
            $table->dropForeign('fft_eeg_reading_foreign');
        });   

        Schema::table('fft_data', function($table)
        {
            $table->dropForeign('fft_data_fft_foreign');
        });    
        Schema::table('fft_band_data', function($table)
        {
            $table->dropForeign('fft_band_data_fft_foreign');
            $table->dropForeign('fft_band_data_band_foreign');
        });                  
    }
}
