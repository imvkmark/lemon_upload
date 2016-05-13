<?php echo '<?php' ?>

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class SlUploadSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // 保存图片存储key
        Schema::create('{{ $image_key }}', function (Blueprint $table) {
            $table->increments('id');
	          $table->integer('account_id')->default(0)->unsigned();
	          $table->string('key_public', 255)->default('')->nullable();
	          $table->string('key_type', 50)->default('')->nullable();
	          $table->string('key_secret', 255)->default('')->nullable();
	          $table->string('key_note', 255)->default('');
            $table->timestamps();

            $table->index('account_id');
            $table->index('key_public');
        });

        // 保存图片上传的表记录
        Schema::create('{{ $image_upload }}', function (Blueprint $table) {
            $table->increments('id');
	          $table->integer('account_id')->unsigned();
            $table->string('upload_type', 50)->default('');
	          $table->string('upload_path', 255)->default('')->nullable();
	          $table->string('upload_extension', 255)->default('')->nullable();
	          $table->integer('upload_filesize')->unsigned();
	          $table->string('upload_mime', 50)->default('');
	          $table->string('upload_field', 50)->default('');
	          $table->integer('upload_width')->unsigned();
	          $table->integer('upload_height')->unsigned();
            $table->string('image_type', 50)->default('');
            $table->integer('image_width')->unsigned();
            $table->integer('image_height')->unsigned();
            $table->timestamps();

						$table->index('account_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('{{ $image_key }}');
        Schema::drop('{{ $image_upload }}');
    }
}
