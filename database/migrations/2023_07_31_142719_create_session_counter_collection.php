<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The name of the database connection to use.
     *
     * @var string
     */
    protected $connection = 'mongodb';

    private string $collectionName;

    public function __construct()
    {
        $this->collectionName = config('phone-verification.storage.mongodb.settings.collection_session_counter');
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
            $collection->dateTime('created');
            $collection->integer('count');
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
