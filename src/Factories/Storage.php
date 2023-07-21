<?php

namespace AlexGeno\PhoneVerificationLaravel\Factories;

use AlexGeno\PhoneVerification\Storage\I as IStorage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class Storage {
    public function redis():IStorage{
        return new \AlexGeno\PhoneVerification\Storage\Redis(Redis::connection()->client());
    }

    public function mongoDb():IStorage{
        return new \AlexGeno\PhoneVerification\Storage\MongoDb(DB::connection('mongodb')->getMongoClient());
    }
}
