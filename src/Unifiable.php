<?php

namespace MarcoTisi\Unifiables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unifiable extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $primaryKey = null;

    /**
     * {@inheritdoc}
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    protected static $unifiableQuery = null;

    protected static $unifiableFields = [
        'title',
        'subtitle',
        'date',
    ];

    protected static $unifiables = [
    ];

    protected static function boot()
    {
        parent::boot();

        foreach (static::$unifiables as $unifiable) {
            static::addUnifiable($unifiable);
        }
    }

    public static function addUnifiable($unifiable, $mappings = [], $callback = null)
    {
        if (is_array($unifiable)) {
            $unifiable = array_first(array_keys($unifiable));
            $mappings = array_values($mappings);
            $callback = $mappings;
        }
        if (is_string($unifiable)) {
            $unifiable = new $unifiable();
        }
        if ($unifiable instanceof Builder) {
            $unifiable = $unifiable->getModel();
        }
        $query = $unifiable->newQuery();
        if (is_callable($callback)) {
            $query = $callback($query, $unifiable, $mappings, $callback);
        }

        $query->selectRaw(\DB::connection()->getPdo()->quote(get_class($unifiable)) . ' AS unifiable_type');
        $query->selectRaw($unifiable->getKeyName() . ' AS unifiable_id');
        foreach (static::$unifiableFields as $unifiableField) {
            if ($mappedField = array_get($mappings, $unifiableField)) {
                $query->selectRaw($mappedField . ' AS ' . $unifiableField);
                continue;
            }
            $query->addSelect($unifiableField);
        }

        if (!static::$unifiableQuery) {
            static::$unifiableQuery = $query;
        } else {
            static::$unifiableQuery->unionAll($query);
        }

        return new static;
    }

    public function getTable()
    {
        return \DB::raw('(' . static::$unifiableQuery->toSql() . ') AS unifiables');
    }

    public function unifiable()
    {
        return $this->morphTo('unifiable');
    }
}
