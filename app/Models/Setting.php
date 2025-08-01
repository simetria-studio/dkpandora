<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description'
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $label = null, $description = null, $group = 'general')
    {
        $setting = static::where('key', $key)->first();
        
        if ($setting) {
            $setting->update([
                'value' => $value,
                'type' => $type,
                'label' => $label ?: $setting->label,
                'description' => $description ?: $setting->description,
                'group' => $group
            ]);
        } else {
            static::create([
                'key' => $key,
                'value' => $value,
                'type' => $type,
                'label' => $label ?: ucfirst(str_replace('_', ' ', $key)),
                'description' => $description,
                'group' => $group
            ]);
        }
    }

    /**
     * Cast value based on type
     */
    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Get all settings grouped
     */
    public static function getAllGrouped()
    {
        return static::orderBy('group')->orderBy('label')->get()->groupBy('group');
    }
}
