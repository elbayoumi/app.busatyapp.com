<?php

namespace App\Models\Traits;

use App\Models\CustomIdentifier;
use App\Models\NotificationText;
use App\Models\TripType;
use Illuminate\Database\Eloquent\Factories\HasFactory;

trait CommonTrait
{
    use HasFactory;
    public function scopePaginateLimit($query)
    {
        $limit = (int) (request()->input('limit', 10));
        $limit = $limit > 0 ? $limit : 10;
        return $query->paginate($limit);
    }
    // private function localize($attribute)
    // {
    //     $locale = $this->getLocale();
    //     $localizedData = json_decode($attribute, true); // Decode as associative array

    //     // Ensure we have valid JSON and a valid locale
    //     if (json_last_error() === JSON_ERROR_NONE && is_array($localizedData)) {
    //         // Return the localized value or fallback to the default locale
    //         return $localizedData[$locale] ?? $localizedData[config('app.fallback_locale')] ?? null;
    //     }

    //     // If JSON is invalid, return null or some default value
    //     return null;
    // }
    private function localize($attribute)
    {
        $locale = $this->getLocale();

        // If $attribute is already an array, no need to decode it
        $localizedData = is_string($attribute) ? json_decode($attribute, true) : $attribute;

        // Ensure we have a valid array (if it was a string and was decoded successfully)
        if (is_array($localizedData)) {
            // Return the localized value or fallback to the default locale
            return $localizedData[$locale] ?? $localizedData[config('app.fallback_locale')] ?? null;
        }

        // If the attribute is not a valid array or JSON, return null
        return null;
    }

    private function getLocale()
    {
        // Retrieve locale from request header or fallback to default locale in app config
        return lang();
    }

    /**
     * Encode the value as JSON.
     *
     * @param array|null $value
     * @return string|null
     */
    private function trJson(?array $value): ?string
    {
        return json_encode([
            'ar' => $value['ar'] ?? null,
            'en' => $value['en'] ?? null,
        ]);
    }
    public function getToNotificationsText()
    {
        return NotificationText::where('to_model_type', self::class)
            ->get();
    }
    public function getForNotificationsText()
    {
        return NotificationText::where('for_model_type', self::class)
            ->get();
    }
    public function customIdentifier()
    {
        return $this->morphOne(CustomIdentifier::class, 'identifiable');
    }
    public function createdTripTypes()
    {
        return $this->morphMany(TripType::class, 'tripable');
    }
    public function joinedTripTypes()
    {
        return $this->morphToMany(TripType::class, 'userable', 'trip_type_users', 'userable_id', 'trip_type_id');
    }
}
