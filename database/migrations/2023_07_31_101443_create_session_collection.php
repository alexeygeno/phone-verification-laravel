<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Only mongodb migration.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    private string $collectionName;

    public function __construct()
    {
        $this->collectionName = config('phone-verification.storage.mongodb.collection_session');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->collectionName, function (Blueprint $collection) {
            $collection->string('id');
            $collection->string('otp');
            $collection->dateTime('updated');
            $collection->integer('otp_check_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->collectionName);
    }
};
