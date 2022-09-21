<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Traits\Model\Autofill;
use App\Traits\Model\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebinarEvent extends BaseModel
{
    public static $uuIdPrefix = ''; // C-

    use SoftDeletes, Autofill, Uuid;

    protected $fillable = [
        'organization_id', 'event_name', 'event_description', 'event_url', 'live_meeting_account_id', 'publish_date_time', 'apply_last_date_time', 'supporting_documents', 'speech_to_text_content', 'event_gist', 'details', 'meeting_id', 'start_at', 'duration', 'password', 'start_url', 'join_url', 'topic', 'status',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    protected $casts = [
        // Integer
        'id' => 'integer',
        'created_by'             => 'integer',
        'updated_by'             => 'integer',
        'organization_id'        => 'integer',
        'duration'               => 'integer',
        'live_meeting_account_id'=> 'integer',
        //Date
        'date'                   => 'date:Y-m-d',
        //Date Time
        'publish_date_time'      => 'datetime:Y-m-d H:i:00',
        'apply_last_date_time'   => 'datetime:Y-m-d H:i:00',
        'start_at'               => 'datetime:Y-m-d H:i:00',
        'created_at'             => 'datetime:Y-m-d H:i:s',
        'updated_at'             => 'datetime:Y-m-d H:i:s',
        // String
        'supporting_documents'   => 'string',
        'event_name'             => 'string',
        'event_description'      => 'string',
        'event_url'              => 'string',
        'speech_to_text_content' => 'string',
        'event_gist'             => 'string',
        'details'                => 'string',
        'status'                 => 'string',
        'meeting_id'             => 'string',
        'password'               => 'string',
        'start_url'              => 'string',
        'join_url'               => 'string',
        'topic'                  => 'string',
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at',
    ];

    protected $attributes = [
        'status' => StatusEnum::ACTIVE,
    ];

}
