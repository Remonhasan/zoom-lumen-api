<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Model\Autofill;
use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebinarEventSessionTime extends BaseModel
{
    public static $uuIdPrefix = ''; // C-

    use SoftDeletes, Autofill, Uuid;

    protected $fillable = [
        'webinar_event_id', 'session_start_date_time', 'session_end_date_time',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        // Integer
        'id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'webinar_event_id' => 'integer',
        // Decimal
        'amount' => 'decimal:4',
        //Date
        'date' => 'date:Y-m-d',
        // Time
        'session_start_date_time' => 'datetime:Y-m-d H:i:00',
        'session_end_date_time' => 'datetime:Y-m-d H:i:00',
        //Date Time
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        // String
        'title' => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $attributes = [

    ];

}
