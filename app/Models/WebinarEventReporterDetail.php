<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Model\Autofill;
use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebinarEventReporterDetail extends BaseModel
{
    public static $uuIdPrefix = ''; // C-

    use SoftDeletes, Autofill, Uuid;

    protected $fillable = [
        'webinar_event_id', 'reporter_name', 'reporter_email', 'reporter_phone', 'reporter_work_deadline', 'reporter_order',
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
        'reporter_order' => 'integer',
        // Decimal
        'amount' => 'decimal:4',
        //Date
        'date' => 'date:Y-m-d',
        //Date Time
        'reporter_work_deadline' => 'datetime:Y-m-d H:i:00',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        // String
        'reporter_name' => 'string',
        'reporter_email' => 'string',
        'reporter_phone' => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $attributes = [

    ];

}
