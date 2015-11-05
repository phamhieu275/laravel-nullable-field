<?php namespace Bluecode\Traits;

/**
 * Nullable (database) fields trait.
 *
 * Include this trait in any Eloquent models you wish to automatically set
 * empty field values to null. When saving, iterate over the model's
 * attributes and if their value is empty, make it null before save.
 *
 * @package    Bluecode
 * @subpackage Traits
 * @copyright  2015
 */

trait NullableField
{
    /**
     * Boot the trait, add a saving observer.
     *
     * When saving the model, we iterate over its attributes and for any attribute
     * marked as nullable whose value is empty, we then set its value to null.
     */
    protected static function bootNullableField()
    {
        static::saving(function ($model) {
            $nullableFields = $model->getNullableField($model->getAttributes());
            foreach ($nullableFields as $field => $value) {
                if (is_scalar($value)) {
                    $value = $model->nullIfEmpty($value);
                }

                $model->setAttribute($field, $value);
            }
        });
    }


    /**
     * If value is empty, return null, otherwise return the original input.
     *
     * @param  string $value
     *
     * @return null|string
     */
    protected function nullIfEmpty($value)
    {
        return trim($value) === '' ? null : $value;
    }


    /**
     * Get the nullable attributes of a given array.
     *
     * @param  array $attributes
     *
     * @return array
     */
    protected function getNullableField($attributes = [])
    {
        if (isset($this->nullable) && is_array($this->nullable) && count($this->nullable) > 0) {
            return array_intersect_key($attributes, array_flip($this->nullable));
        }

        return [];
    }
}
