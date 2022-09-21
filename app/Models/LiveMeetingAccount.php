<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Model\Uuid;
use App\Traits\Model\Autofill;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveMeetingAccount extends BaseModel
{
    public static $uuIdPrefix = ''; // C-

    use  SoftDeletes, Autofill, Uuid;

    protected $fillable = [
        'organization_id', 'platform_type', 'name', 'component_ids','email', 'api_key', 'api_secret','status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        // Integer
        'id'            => 'integer',
        'created_by'    => 'integer',
        'updated_by'    => 'integer',
        'status'        => 'integer',
        'organization_id'=> 'integer',
        // Decimal
        'amount'        => 'decimal:4',
        //Date
        'date'          => 'date:Y-m-d',
        //Date Time
        'created_at'    => 'datetime:Y-m-d H:i:s',
        'updated_at'    => 'datetime:Y-m-d H:i:s',
        //Json
        'component_ids'   => 'json',
        // String
        'platform_type' => 'string',
        'name'          => 'string',
        'email'         => 'string',
        'api_key'       => 'string',
        'api_secret'    => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $attributes = [
        'status' => StatusEnum::ACTIVE,
    ];

}
