<?php

namespace MarcoTisi\Unifiables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Unifiable extends Model
{
    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected static $builder = null;

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
        $builder = $unifiable->newQuery();
        if (is_callable($callback)) {
            $builder = $callback($builder, $unifiable, $fields, $callback);
        }

        $builder->selectRaw(app('db')->connection()->getPdo()->quote($class).' AS unifiable_type');
        $builder->selectRaw($unifiable->getKeyName().' AS unifiable_id');
        foreach (static::$unifiableFields as $unifiableField) {
            if ($mappedField = array_get($fields, $unifiableField)) {
                $builder->selectRaw($mappedField.' AS '.$unifiableField);
                continue;
            }
            $builder->addSelect($unifiableField);
        }

        if (! static::$builder) {
            static::$builder = $builder;
        } else {
            static::$builder->unionAll($builder);
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
        $query = static::$builder->getQuery();
        $grammar = $query->getGrammar();
        return $query->raw('('.$query->toSql().') AS ' . $grammar->wrap('unifiables'));
    }

    public function unifiable()
    {
        return $this->morphTo('unifiable');
    }
}
