<?php

namespace App\Repositories;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseRepository
{
    /**
     * @var array
     */
    private $allowed_operator = ['>', '>=', '=', '!=', '<>', '<', '<=', 'like', 'not like', 'in', 'not in', 'Null', 'NotNull'];

    /**
     * @var array
     */
    private $allowed_order = ["asc", "desc"];

    /**
     * @var App
     */
    private $app;

    /**
     * @var
     */
    protected $model;
    protected $query;

    /**
     * EloquentRepository constructor.
     *
     * @param App $app
     *
     * @throws BindingResolutionException
     */
    public function __construct (App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Specify Model class name
     * @return mixed
     */
    abstract function model ();

    /**
     * @return mixed
     * @throws BindingResolutionException
     */
    public function makeModel ()
    {
        $model = $this->app->make($this->model());
        return $this->model = $model;
    }

    public function newQuery ()
    {
        $this->query = $this->model->newQuery();
    }

    public function all (array $columns = ['*'])
    {
        $this->newQuery();
        return $this->query->get($columns);
    }

    public function findWhere (array $condition = [], array $columns = ['*'], int $limit = null,
                               int   $offset = null, array $orderBy = [])
    {
        //reset model
        $this->makeModel();
        $this->addCondition($condition);
        if ($offset)
        {
            $this->model = $this->model->offset($offset);
        }
        if ($limit)
        {
            $this->model = $this->model->limit($limit);
        }
        $this->orderBy($orderBy);
        $result = $this->model->get($columns);
        if ($result && count($result) > 0)
        {
            return $result;
        }
        else
        {
            return [];
        }
    }

    public function find ($id, array $columns = ['*'])
    {
        //reset model
        $this->makeModel();

        return $this->model->find($id, $columns);
    }

    public function findOne ($attribute, $value, array $columns = ['*'])
    {
        //reset model
        $this->makeModel();

        return $this->model->where($attribute, "=", $value)->first($columns);
    }

    public function insert (array $data)
    {
        //reset model
        $this->makeModel();

        return $this->model->insert($data);
    }

    public function countWhere (array $condition = [])
    {
        //reset model
        $this->makeModel();

        $this->addCondition($condition);
        return $this->model->count();
    }

    public function count ()
    {
        //reset model
        $this->makeModel();

        return $this->model->count();
    }

    public function checkExist ($id)
    {
        //reset model
        $this->makeModel();

        return $this->model->find($id) ? true : false;
    }

    public function updateExistingPivot (string $lcId, string $fkIds, string $relationship,
                                         array  $values)
    {
        $this->newQuery();
        $this->query->find($lcId)->{$relationship}()->updateExistingPivot($fkIds, $values);
    }

    public function increment (string $column, array $conditions = [], int $step = 1,
                               array  $scopes = [])
    {
        $this->newQuery();
        $this->_addWhere($conditions);
        $this->_addScopes($scopes);
        $this->query->increment($column, $step);
    }

    public function incrementById (string $column, $id, int $step = 1,
                                   array  $scopes = [])
    {
        $this->newQuery();
        $this->query->where('id', '=', $id);
        $this->_addScopes($scopes);
        $this->query->increment($column, $step);
    }

    public function incrementByIds (string $column, array $ids, int $step = 1,
                                    array  $scopes = [])
    {
        $this->newQuery();
        $this->query->whereIn('id', $ids);
        $this->_addScopes($scopes);
        $this->query->increment($column, $step);
    }

    public function update ($attribute, $value, array $data)
    {
        //reset model
        $this->makeModel();

        return ($this->model->where($attribute, '=', $value)->update($data));
    }

    public function updateOrCreate ($conditions, array $data)
    {
        $this->makeModel();
        return $this->model->updateOrCreate($conditions, $data);
    }

    public function updateWhere (?array $condition, array $data)
    {
        //reset model
        $this->makeModel();

        $this->addCondition($condition);
        return $this->model->update($data);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate (int $perPage = 1, array $columns = ['*']) : LengthAwarePaginator
    {
        $this->newQuery();
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create (array $data)
    {
        //reset model
        $this->makeModel();

        return $this->model->create($data);
    }

    public function delete ($id)
    {
        //reset model
        $this->makeModel();

        return $this->model->destroy($id);
    }

    public function deleteWhere (array $condition)
    {
        //reset model
        $this->makeModel();

        $this->addCondition($condition);
        return $this->model->delete();
    }

    public function checkExistBy (array $condition)
    {
        //reset model
        $this->makeModel();
        $this->addCondition($condition);
        $data = $this->model->get();
        if ($data->isEmpty())
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
     * @param array $conditions
     *
     * @return boolean
     */
    private function validateCondition (array $conditions = [])
    {
        foreach ($conditions as $condition)
        {
            if (!is_array($condition))
            {
                die("condition error");
            }

            $attribute = $condition[0];
            $operator  = $condition[1];

            if (!in_array($operator, $this->allowed_operator))
            {
                die("condition error");
            }
        }

        return true;
    }

    private function validateOrderBy (array $orderBy = [])
    {
        $check = true;
        if (!$orderBy || !is_array($orderBy))
        {
            $check = false;
        }

        if (!isset($orderBy[0]) || !isset($orderBy[1]))
        {
            $check = false;
        }

        $order = isset($orderBy[1]) ? $orderBy[1] : '';
        if (!in_array($order, $this->allowed_order))
        {
            $check = false;
        }

        return $check;
    }

    protected function orderBy (array $orderBys = [])
    {

        //$orderBy is a empty array
        if (!$orderBys || !is_array($orderBys))
        {
            return $this->model;
        }

        if (!isset($orderBys[0]) || !is_array($orderBys[0]))
        {
            $orderBys = [
                0 => $orderBys,
            ];
        }

        foreach ($orderBys as $orderBy)
        {
            $check = $this->validateOrderBy($orderBy);
            if (!$check)
            {
                continue;
            }
            $attribute   = $orderBy[0];
            $order       = $orderBy[1];
            $this->model = $this->model->orderBy($attribute, $order);
        }

        return $this->model;
    }

    /**
     * @param array $conditions
     *
     * @return bool|mixed|null
     */
    protected function addCondition (array $conditions = [])
    {
        $this->validateCondition($conditions);

        foreach ($conditions as $condition)
        {

            $attribute = $condition[0];
            $operator  = $condition[1];
            $value     = null;
            if (isset($condition[2]))
            {
                $value = $condition[2];
            }
            if ($operator == "=")
            {
                $this->model = $this->model->where($attribute, "=", $value);
            }

            if ($operator == ">")
            {
                $this->model = $this->model->where($attribute, ">", $value);
            }

            if ($operator == ">=")
            {
                $this->model = $this->model->where($attribute, ">=", $value);
            }

            if ($operator == "<")
            {
                $this->model = $this->model->where($attribute, "<", $value);
            }

            if ($operator == "<=")
            {
                $this->model = $this->model->where($attribute, "<=", $value);
            }

            if ($operator == "<>")
            {
                $this->model = $this->model->where($attribute, "<>", $value);
            }

            if ($operator == "!=")
            {
                $this->model = $this->model->where($attribute, "!=", $value);
            }

            if ($operator == "in")
            {
                $this->model = $this->model->whereIn($attribute, $value);
            }

            if ($operator == "not int")
            {
                $this->model = $this->model->whereNotIn($attribute, $value);
            }

            if ($operator == "like")
            {
                $this->model = $this->model->where($attribute, "like", $value);
            }

            if ($operator == "not like")
            {
                $this->model = $this->model->where($attribute, "not like", $value);
            }

            if ($operator == "Null")
            {
                $this->model = $this->model->whereNull($attribute);
            }

            if ($operator == "NotNull")
            {
                $this->model = $this->model->whereNotNull($attribute);
            }

        }

        return $this->model;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findOrFail ($id)
    {
        //reset model
        $this->makeModel();

        return $this->model->findOrFail($id);
    }


    public function updateById ($id, array $data)
    {
        $this->newQuery();
        $this->query->find($id)->update($data);
    }

    public function updateByIds (array $ids, array $data)
    {
        $this->newQuery();
        $this->query->find($ids)->update($data);
    }

    public function upsert (array $values, array $uniqueColumns = [], array $columnsUpdate = [])
    {
        if (empty($columnsUpdate))
        {
            $columnsUpdate['id'] = DB::raw('id');
        }

        $this->newQuery();
        $this->query->upsert($values, $uniqueColumns, $columnsUpdate);
    }

    public function updateOrInsert (array $conditions = [], array $updateValues = [])
    {
        $this->newQuery();
        $this->query->updateOrInsert($conditions, $updateValues);
    }

    public function sUpdateOrCreate (array $conditions = [], array $updateValues = []) : Model
    {
        $this->newQuery();
        return $this->query->updateOrCreate($conditions, $updateValues);
    }

    public function sFind (array $columns = ['*'], array $conditions = [], array $orders = [],
                           array $limitOffset = [], array $scopes = [],
                           array $postFunctions = []) : Collection
    {
        $this->newQuery();
        $this->query->select($columns);
        $this->_addWhere($conditions);
        $this->_addScopes($scopes);
        $this->_addOrderBy($orders);
        $this->_addLimitOffset($limitOffset);
        $result = $this->query->get();
        $this->_addPostFunction($result, $postFunctions);
        return $result;
    }

    public function sFindOne (array $columns = ['*'], array $conditions = [], array $orders = [],
                              array $limitOffset = [], array $scopes = [],
                              array $postFunctions = []) : ?Model
    {
        return $this->sFind(...func_get_args())[0] ?? null;
    }

    public function findByIds (array $ids, array $columns = ['*'], array $orders = [],
                               array $limitOffset = [], array $postFunctions = []) : Collection
    {
        $this->newQuery();
        $this->_addOrderBy($orders);
        $this->_addLimitOffset($limitOffset);
        $result = $this->model->find($ids, $columns);
        $this->_addPostFunction($result, $postFunctions);
        return $result;
    }


    public function findById (int   $id, array $columns = ['*'], array $orders = [],
                              array $limitOffset = [], array $postFunctions = []) : ?Model
    {
        $this->newQuery();
        $this->_addOrderBy($orders);
        $this->_addLimitOffset($limitOffset);
        $result = $this->model->find($id, $columns);
        $this->_addPostFunction($result, $postFunctions);
        return $result;
    }

    public function sFindBySLug (string $slug, array $columns = ['*'], array $scopes = [],
                                 array  $postFunctions = []) : ?Model
    {
        $this->newQuery();
        return $this->sFindOne($columns, [['slug', '=', $slug]], [], [], $scopes, $postFunctions);
    }

    public function chunk (int $recordsPerChunk, Closure $closure, array $columns = ['*'], array $conditions = [], array $scopes = [])
    {
        $this->newQuery();
        $this->_addWhere($conditions);
        $this->_addScopes($scopes);
        $this->query->select($columns)->chunk($recordsPerChunk, $closure);
    }

    public function sPaginate (int   $perPage = 10, array $columns = ['*'], array $conditions = [], array $orders = [],
                               array $scopes = []) : LengthAwarePaginator
    {
        $this->newQuery();
        $this->query->select($columns);
        $this->_addWhere($conditions);
        $this->_addOrderBy($orders);
        $this->_addScopes($scopes);
        return $this->query->paginate($perPage);
    }

    protected function _addScopes (array $scopes) : void
    {
        if (empty($scopes))
        {
            return;
        }

        foreach ($scopes as $arr)
        {
            $scope       = array_shift($arr);
            $this->query = $this->query->$scope(...$arr);
        }
    }

    protected function _addWhere (array $conditions = []) : void
    {
        if (empty($conditions))
        {
            return;
        }

        foreach ($conditions as $condition)
        {
            $attribute = $condition[0];
            $operator  = $condition[1];
            $value     = $condition[2] ?? null;

            $method = 'where';
            if (strpos($operator, '|') !== false)
            {
                $operator = str_replace('|', '', $operator);
                $method   = 'orWhere';
            }

            switch ($operator)
            {
                case 'between':
                    $this->query = $this->query->{"{$method}Between"}($attribute);
                    break;
                case 'in':
                    $this->query = $this->query->{"{$method}In"}($attribute, $value);
                    break;
                case 'not in':
                    $this->query = $this->query->{"{$method}NotIn"}($attribute, $value);
                    break;
                case 'null':
                    $this->query = $this->query->{"{$method}Null"}($attribute);
                    break;
                case 'not null':
                    $this->query = $this->query->{"{$method}NotNull"}($attribute);
                    break;
                default:
                    $this->query = $this->query->{$method}($attribute, $operator, $value);
            }
        }
    }

    protected function _addOrderBy (array $orders = []) : void
    {
        if (empty($orders))
        {
            return;
        }

        foreach ($orders as $order)
        {
            $this->query = $this->query->orderBy(...$order);
        }
    }

    protected function _addLimitOffset (array $pagination) : void
    {
        if (empty($pagination))
        {
            return;
        }

        if (count($pagination) == 1)
        {
            $this->query = $this->query->take($pagination[0]);
        }
        else
        {
            $this->query = $this->query->limit($pagination[0])->offset($pagination[1]);
        }
    }

    protected function _addPostFunction (&$result, array $functions) : void
    {
        if (empty($functions))
        {
            return;
        }

        foreach ($functions as $arr)
        {
            $function = array_shift($arr);
            $result   = $result->$function(...$arr);
        }
    }
}
