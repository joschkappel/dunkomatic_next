<?php

namespace App\Models;

use App\Models\Schedule;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ScheduleEvent
 *
 * @property int $id
 * @property int $schedule_id
 * @property int $game_day
 * @property \Illuminate\Support\Carbon $game_date
 * @property bool $full_weekend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Schedule $schedule
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereFullWeekend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereGameDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereGameDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ScheduleEvent whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduleEvent extends Model
{
  protected $fillable = [
        'id','schedule_id','game_day','game_date','full_weekend'
    ];

  protected $dates = ['game_date'];

  protected $casts = [
        'full_weekend' => 'boolean',
  ];

  public function schedule()
  {
      return $this->belongsTo(Schedule::class);
  }
}
