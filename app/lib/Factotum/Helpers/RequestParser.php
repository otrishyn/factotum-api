<?php

namespace Factotum\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Request;

/**
 * Class RequestParser
 *
 * @package Factotum\Helpers
 */
class RequestParser
{
    /**
     *
     */
    const DISALLOWED_FIELD_CHARACTERS = " \t\n\r\0\x0B\"'";
    /**
     * @var \Request
     */
    private $request;
    /**
     * @var \stdClass
     */
    private $rawQuery;
    /**
     * @var string|null
     */
    private $entityName;

    /**
     * RequestParser constructor.
     *
     * @param \Request $request
     * @param null $entityName
     */
    public function __construct(Request $request, $entityName = null)
    {
        $this->request = $request;

        $json = $request->get('find', '{}');

        // This is a strange json_decode fix. Strings like 6123-5124 (without quotes) are not always treated correctly
        // by json_decode (which should return NULL in that case), but returns an int(6123), so only taking the first
        // part. This causes issues when searching. So, when a find entry isn't starting with double-qoutes (") or
        // brackets [] {}, we assume it's a string, and add brackets to it so it will be cast properly within
        // json_decode.
        if (strlen($json) == 0 || ! in_array($json[0], ['"', '{', '['])) {
            $json = '"' . $json . '"';
        }

        $this->rawQuery = json_decode($json);
        if (is_null($this->rawQuery) && $request->has('find')) {
            $this->rawQuery = $request->get('find');
        }

        $this->fields = $this->parseCustomFields();
        $this->entityName = $entityName;
    }

    /**
     * @param Model $model
     * @return Model
     */
    public function getQuery($model)
    {
        foreach ($this->rawQuery as $key => $value) {
            $model = $this->buildQuery($model, $key, $value);
        }

        return $this->parseSort($model);
    }

    /**
     * @param string $default
     * @return \stdClass
     */
    private function getSort($default = '{}')
    {
        return json_decode($this->request->get('sort', $default));
    }
    
    /**
     * @return array
     */
    private function parseCustomFields()
    {
        if (! $this->request->has('fields')) {
            return ['*'];
        }
        $fields = $this->request->get('fields');
        $customFields = [];
        if (is_array($fields)) {
            foreach ($fields as $type => $fieldlist) {
                $customFields[trim($type, self::DISALLOWED_FIELD_CHARACTERS)] = explode(',', $fieldlist);
            }
        } else {
            $customFields[$this->entityName] = explode(',', $fields);
        }

        return $customFields;
    }


    /**
     * @return bool
     */
    private function wantsCustomFields()
    {
        return ! in_array('*', $this->fields);
    }

    /**
     * @return array
     */
    public function getCustomFields()
    {
        if (! $this->wantsCustomFields()) {
            return ['*'];
        }
        if (isset($this->fields[$this->entityName])) {
            return $this->fields[$this->entityName];
        }

        return ['*'];
    }

    /**
     * @param Model $model
     * @return Model
     */
    private function parseSort($model)
    {
        foreach ($this->getSort() as $key => $value) {
            if ($model instanceof Collection) {
                if (strtoupper($value) === 'ASC') {
                    $model = $model->sortBy($key);
                } else {
                    $model = $model->sortByDesc($key);
                }
            } else {
                $model = $model->orderBy($key, $value);
            }
        }

        return $model;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|Builder|Model $model
     * @param mixed $key
     * @param mixed $value
     * @return Model
     */
    public function buildQuery($model, $key, $value)
    {
        if (is_object($value) && isset($value->between)) {
            return $model->whereBetween($key, $value->between);
        }
        if (is_object($value) && isset($value->in)) {
            $value = $value->in;
        } elseif (is_object($value)) {
            foreach ((array) $value as $operator => $val) {
                $model->where($key, $operator, $val);
            }

            return $model;
        }

        if (is_array($value)) {
            return $model->whereIn($key, array_values($value));
        }

        $wildcard = (bool) preg_match('/\*/', $value);
        if (! $wildcard) {
            $keys = explode('.', $key);

            return $model->whereHas(
                $keys[0],
                function ($q) use ($keys, $value) {
                    $q->where($keys[1], '=', $value);
                }
            );
        }

        $value = str_replace('*', '%', $value);
        if (strpos($key, '.') === false) {
            return $model->where($key, 'like', $value);
        }

        $keys = explode('.', $key);

        return $model->whereHas(
            $keys[0],
            function ($q) use ($keys, $value) {
                $q->where($keys[1], 'like', $value);
            }
        );
    }
}