<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'uri',
        'port',
        'interval',
        'group_id',
        'device_name'
    ];


    public function checks(): HasMany
    {
        return $this->hasMany(Check::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function latestCheck(): HasOne
    {
        // return $this->hasOne(Check::class)->withDefault([
        //     'status' => '0',        
        // ]);
        return $this->hasOne(Check::class)->latestOfMany();
    }
    public function eventLogs(): HasMany
    {
        return $this->hasMany(EventLog::class);
    }
    
}
