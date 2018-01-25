<?php

namespace Laraquick\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Helper
{

    /**
     * A shortcut to withoutGlobalScope()
     *
     * @param string|array $attributes
     * @return Builder
     */
    public function without($attributes)
    {
        return $this->withoutGlobalScope($attributes);
    }
    
    /**
     * Excludes the given values from being selected from the database
     * Thanks to Ikechi Michael (@mykeels)
     *
     * @param Builder $query
     * @param string|array $value
     * @return void
     */
    public function scopeExcept($query, $value)
    {
        $defaultColumns = ['id', 'created_at', 'updated_at'];
        if (in_array_('deleted_at', $this->dates)) {
            $defaultColumns[] = 'deleted_at';
        }
        if (is_string($value)) {
            $value = [$value];
        }
        return $query->select(array_diff(array_merge($defaultColumns, $this->fillable), (array) $value));
    }
    
    public function toArray()
    {
        $fillable = $this->fillable;
        $fillable[] = 'id';
        // Show only fillables
        $array = collect(parent::toArray())
            ->only($fillable)
            ->all();
        // Add loaded relations
        foreach (array_keys($this->relations) as $relation) {
            $array[$relation] = $this->$relation;
        }
        return $array;
    }
}