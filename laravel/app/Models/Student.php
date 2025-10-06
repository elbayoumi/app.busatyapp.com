<?php
namespace App\Models;

use App\Enum\TripStudentStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class Student extends Model
{
    use Traits\CommonTrait;

    protected $keyType   = 'string';
    public $incrementing = false; // لتعطيل الـ auto-increment
                                  // protected $guarded = [];
    protected $appends  = ['logo_path'];
    protected $fillable = [
        'id',
        'name',
        'phone',
        'grade_id',
        'gender_id',
        'school_id',
        'classroom_id',
        'bus_id',
        'address',
        'status',
        'trip_type',
        'parent_key',
        'parent_secret',
        'logo',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'id'            => 'string',   // Cast big integer to integer
        'name'          => 'string',   // Cast varchar to string
        'phone'         => 'string',   // Cast varchar to string
        'grade_id'      => 'integer',  // Cast big integer to integer
        'gender_id'     => 'integer',  // Cast big integer to integer
        'school_id'     => 'integer',  // Cast big integer to integer
                                       // 'religion_id' => 'integer', // Cast big integer to integer
                                       // 'type__blood_id' => 'integer', // Cast big integer to integer
        'classroom_id'  => 'integer',  // Cast big integer to integer
        'bus_id'        => 'integer',  // Cast big integer to integer
        'address'       => 'string',   // Cast varchar to string
                                       // 'city_name' => 'string', // Cast varchar to string
        'status'        => 'integer',  // Cast int to integer
        'trip_type'     => 'string',   // Cast enum to string
        'parent_key'    => 'string',   // Cast varchar to string
        'parent_secret' => 'string',   // Cast varchar to string
                                       // 'Date_Birth' => 'date', // Cast date to date
        'logo'          => 'string',   // Cast varchar to string
        'latitude'      => 'string',   // Cast varchar to string (or float if using decimal values)
        'longitude'     => 'string',   // Cast varchar to string (or float if using decimal values)
        'created_at'    => 'datetime', // Cast timestamp to datetime
        'updated_at'    => 'datetime', // Cast timestamp to datetime
        'pivot.status'  => TripStudentStatusEnum::class,

    ];
    public function getLogoPathAttribute()
    {
        if (!empty($this->logo)) {
            return asset('storage/students_logo/' . $this->logo);
        }
        return null;
    }


    public function schools()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function tripStudents()
    {
        return $this->hasMany(TripStudent::class, 'student_id');
    }

    /**
     * Relationship: Student has many Trips (through TripStudent)
     */
    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_students', 'student_id', 'trip_id')
            ->withTimestamps();
    }


    /**
     * Get the gender associated with the student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class);
    }
    public function typeBlood()
    {
        return $this->belongsTo(Type_Blood::class, 'type__blood_id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id');
    }
    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
    /**
     * Scope a query to only include students that are waiting for attendance based on the given parameters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $attendence_type
     * @param  int  $attendence_status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWaiting($query, $attendence_type, $attendence_status = 0)
    {
        $bus_id = request()?->user()?->bus_id ?? $this->bus_id;
        return $query->select('id', 'name', 'longitude', 'latitude', 'trip_type','logo', 'school_id', 'bus_id')
            ->with('my_Parents')
            ->where('bus_id', $bus_id)
            ->whereIn('trip_type', [$attendence_type, 'full_day'])
            ->notAbsent($attendence_type)
            ->notAttendance($attendence_type, $attendence_status);
    }
    public function scopeNotAttendance($query, $type, $status)
    {
        // $attendence_date=Carbon::now()->format('Y-m-d');

        return $query->whereDoesntHave('attendance', function ($query) use ($type, $status) {
            $query->where('attendence_date', Carbon::now()->format('Y-m-d'))->whereIn('attendance_type', ['full_day', $type])->where('attendence_status', $status);
        });
    }
    public function scopeNotAbsent($query, $type)
    {
        $attendence_date = Carbon::now()->format('Y-m-d');

        return $query->whereDoesntHave('absences', function ($query) use ($type, $attendence_date) {
            $query->where('attendence_date', $attendence_date)->whereIn('attendence_type', ['full_day', $type]);
        });
    }
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }

    public function attendantParentMessage()
    {
        return $this->hasMany(AttendantParentMessage::class, 'student_id');
    }
    public function address()
    {
        return $this->hasMany(Address::class, 'student_id');
    }

    public function temporaryAddresses()
    {
        return $this->hasMany(TemporaryAddress::class, 'student_id');
    }

    public function my_Parents()
    {
        return $this->belongsToMany(My_Parent::class, 'my__parent_student');
    }
    public function my__parents()
    {
        return $this->belongsToMany(My_Parent::class, 'my__parent_student');
    }
    public function my__parent_student()
    {
        return $this->hasMany(My__parent_student::class, 'student_id');
    }
    public function parentsId()
    {
        return $this->my__parent_student()->pluck('my__parent_id')->toArray();
    }
    public function notifications()
    {

        return NotificationParant::whereIn('parant_id', $this->parentsId());
    }
    public function createNotifications($commonValue)
    {
        $newData = collect($this->parentsId())->map(function ($row) use ($commonValue) {
            $row = collect($this->parentsId())->map(function ($row) {
                return ['parant_id' => $row];
            })->toArray();
            return $commonValue + $row;
        })->toArray();

        // Store the data in the database
        return NotificationParant::create($newData);
    }
    // Mutator: Modify the logo before saving it in the database
    public function setLogoAttribute($value)
    {
        if ($value instanceof UploadedFile) {
            $name = (string) Str::uuid().'.'.$value->getClientOriginalExtension();
            $img  = Image::make($value)->resize(300, null, fn($c) => $c->aspectRatio());
            Storage::disk('public')->put("students_logo/{$name}", (string) $img->encode());
            $this->attributes['logo'] = $name;
            return;
        }
        if (is_string($value)) {
            $this->attributes['logo'] = $value;
        }
    }
    public function attendance()
    {
        return $this->hasMany('App\Models\Attendance', 'student_id');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class, 'student_id');
    }
    public function tr_trip_type()
    {
        switch ($this->trip_type) {
            case 'full_day':
                return 'صباحا و مساء';
            case 'end_day':
                return 'مساء';
            case 'start_day':
                return 'صباحا';
            default:
                return 'Undefined';
        }
    }
    public function en_trip_type()
    {
        switch ($this->trip_type) {
            case 'full_day':
                return 'Morning and Evening';
            case 'end_day':
                return 'Evening';
            case 'start_day':
                return 'Morning';
            default:
                return 'Undefined';
        }
    }
    // Use the boot method to listen to the 'creating' event
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            // Assign random values to parent_key and parent_secret
            $student->parent_key    = randKey();
            $student->parent_secret = randKey();

            // $student->save();

            // $student->customIdentifier()->create([
            // ]);
            if (empty($student->id)) {
                $student->id = Str::uuid()->toString();
            }

        });

    }

}
