<?php

namespace MarcoTisi\Unifiables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unifiable extends Model
{
    protected static $unifiableQuery = null;

    protected static $unifiableFields = [
    ];

    protected static $unifiables = [
    ];

    /**
     * {@inheritdoc}
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $primaryKey = null;

    public static function addUnifiable($unifiable, $fields = [], $callback = null)
    {
        if (is_array($unifiable)) {
            $unifiable = array_first(array_keys($unifiable));
            $fields = array_values($fields);
            $callback = $fields;
        }
        if (is_string($unifiable)) {
            $unifiable = app($unifiable);
        }
        if ($unifiable instanceof Builder) {
            $unifiable = $unifiable->getModel();
        }
        $class = get_class($unifiable);
        $query = $unifiable->newQuery();
        if (is_callable($callback)) {
            $query = $callback($query, $unifiable, $fields, $callback);
        }

        $query->selectRaw(\DB::connection()->getPdo()->quote($class) . ' AS unifiable_type');
        $query->selectRaw($unifiable->getKeyName() . ' AS unifiable_id');
        foreach (static::$unifiableFields as $unifiableField) {
            if ($mappedField = array_get($fields, $unifiableField)) {
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

        return static::$unifiables[$class] = $fields;
    }

    /**
     * @return array
     */
    public function getUnifiableFields(): array
    {
        return static::$unifiableFields;
    }

    /**
     * @param array $unifiableFields
     */
    public function setUnifiableFields(array $unifiableFields)
    {
        static::$unifiableFields = $unifiableFields;
    }

    /**
     * @return array
     */
    public function getUnifiables(): array
    {
        return static::$unifiables;
    }

    /**
     * @param array $unifiables
     */
    public function setUnifiables(array $unifiables)
    {
        static::$unifiables = $unifiables;
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
