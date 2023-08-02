<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
        Schema::table($this->collectionName, function (Blueprint $collection) {
            $collection->index(['id' => 1], 'id_unique_index', null, ['unique' => true]);
            $collection->index(['updated' => 1], 'updated_expiration_index', null,
                ['expireAfterSeconds' => config('phone-verification.manager.rate_limits.initiate.period_secs')]
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->collectionName, function (Blueprint $collection) {
            $collection->dropIndex('id_unique_index');
            $collection->dropIndex('updated_expiration_index');
        });
    }
};
