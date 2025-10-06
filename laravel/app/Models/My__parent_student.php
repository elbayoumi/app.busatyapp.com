<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class My__parent_student extends Model
{
    use Traits\CommonTrait;
    protected $guarded = [];
    protected $table='my__parent_student';

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    public function my_parent()
    {
        return $this->belongsTo(My_Parent::class, 'my__parent_id');
    }
    protected $casts = [
        'id' => 'integer',                // Cast to integer
        'my__parent_id' => 'integer',     // Cast to integer
        'student_id' => 'string',        // Cast to integer
        'created_at' => 'datetime',       // Cast to datetime
        'updated_at' => 'datetime',       // Cast to datetime
    ];
    public static function createOrUpdate($parent_id, $student_id)
    {
        // تحقق مما إذا كان السجل موجودًا بالفعل
        $record = self::where('my__parent_id', $parent_id)
                      ->where('student_id', $student_id)
                      ->first();

        if ($record) {
            // إذا كان موجودًا، يمكنك تحديثه إذا كان هناك حاجة لذلك
            return $record; // أو return $record->update([...]) إذا كنت تريد تحديثه
        } else {
            // إذا لم يكن موجودًا، قم بإنشاء السجل
            return self::create([
                'my__parent_id' => $parent_id,
                'student_id' => $student_id,
            ]);
        }
    }
}
