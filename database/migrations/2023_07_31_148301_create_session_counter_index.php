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
        $this->collectionName = config('phone-verification.storage.mongodb.settings.collection_session_counter');
    }

    /**
     * The name of the database connection to use.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->collectionName, function (Blueprint $collection) {
            /* @phpstan-ignore-next-line */
            $collection->index(['id' => 1], 'id_unique_index', null, ['unique' => true]);
            /* @phpstan-ignore-next-line */
            $collection->index(['created' => 1], 'created_expiration_index', null,
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
            $collection->dropIndex('created_expiration_index');
        });
    }
};
