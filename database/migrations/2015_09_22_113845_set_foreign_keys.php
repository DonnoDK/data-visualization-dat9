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
            $table->foreign('test_case_id')->references('id')->on('test_case')->onDelete('cascade');;
            $table->foreign('test_image_id')->references('id')->on('test_image');
        });
        Schema::table('eeg_reading', function($table)
        {
            $table->foreign('channel_id')->references('id')->on('eeg_channel');
            $table->foreign('test_case_id')->references('id')->on('test_case')->onDelete('cascade');;
        });
        
        Schema::table('gsr_reading', function($table)
        {
            $table->foreign('test_case_id')->references('id')->on('test_case')->onDelete('cascade');;
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
            $table->dropForeign('test_case_test_person_id_foreign');
        });

        Schema::table('test_data', function($table)
        {
            $table->dropForeign('test_data_test_person_id_foreign');
            $table->dropForeign('test_data_test_case_id_foreign');
            $table->dropForeign('test_data_test_image_id_foreign');
        });

        Schema::table('eeg_reading', function($table)
        {
            $table->dropForeign('eeg_reading_channel_id_foreign');
            $table->dropForeign('eeg_reading_test_case_id_foreign');
        });   


        Schema::table('gsr_reading', function($table)
        {
            $table->dropForeign('gsr_reading_test_case_id_foreign');
        });                      
    }
}
