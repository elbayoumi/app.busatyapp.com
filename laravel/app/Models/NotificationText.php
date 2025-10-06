<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class NotificationText extends Model
{
    use Traits\CommonTrait;

    protected $table = 'notification_texts';
    protected $appends = ['title_tr','body_tr'];
    protected $casts = [
        'title' => 'json',
        'body' => 'json',
        'default_body' => 'json',
        'type' => 'json', // Assuming type can also be stored as JSON
        'model_additional' => 'json',

    ];

    protected $fillable = [
        'id',
        'title',
        'body',
        'default_body',
        'type',
        'for_model_type',
        'to_model_type',
        'model_additional',
        'group',
    ];

    public function for()
    {
        return $this->morphTo(null, 'for_model_type'); // استخدام userable_type فقط
    }
    public function to()
    {
        return $this->morphTo(null, 'to_model_type'); // استخدام userable_type فقط
    }

    public function getTitleTrAttribute()
    {
        return $this->localize($this->title);
    }



    /**
     * Set the title attribute.
     *
     * @param array|null $value
     * @return void
     */
    public function setTitleAttribute(?array $value)
    {
        $this->attributes['title'] = $this->trJson($value);
    }

    public function getBodyTrAttribute()
    {
        return $this->localize($this->body);
    }
    public function getDefaultBodyTrAttribute()
    {
        return $this->localize($this->default_body);
    }
    /**
     * Set the body attribute.
     *
     * @param array|null $value
     * @return void
     */
    public function setBodyAttribute(?array $value)
    {
        $this->attributes['body'] = $this->trJson($value);
    }
        /**
     * Set the body attribute.
     *
     * @param array|null $value
     * @return void
     */
    public function setDefaultBodyAttribute(?array $value)
    {

        $this->attributes['default_body'] =  json_encode([
            'ar' => removeClassWord($value['ar']) ?? null,
            'en' => removeClassWord($value['en']) ?? null,
        ]);
    }
    // model_additional
    public function setModelAdditionalAttribute($value)
    {
        $this->attributes['model_additional'] = json_encode([
                'for_model_additional' => $value['for_model_additional'] ?? [],
                'to_model_additional' => $value['to_model_additional'] ?? [],
        ]);
    }

    public static function getAllModels()
    {
        $models = []; // قم بتهيئة مصفوفة الموديلات

        foreach (glob(app_path('Models') . '/*.php') as $file) {
            $className = 'App\\Models\\' . basename($file, '.php');
            if (class_exists($className) && is_subclass_of($className, Model::class)) {
                $additional = defined("$className::additional") ? $className::additional : [];
                $models[] = [
                    'className'=>$className,
                    'additional'=>$additional
                ]; // أضف اسم الموديل إلى المصفوفة
            }
        }
        return $models; // أعد المصفوفة المليئة بالموديلات
    }



}
