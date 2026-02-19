<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Classes;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{

    public function __construct(protected Model $model)
    {
    }

    public function create(array $data): mixed
    {
        return $this->model->create($data);
    }

    public function listAllPaginate(array $condition = [])
    {
        return $this->model->where($condition)->orderBy('created_at', 'desc')->paginate();
    }

    public function listAllBy(array $conditions = [], array $relations = [], array $select = ['*']): array|Collection
    {
        return $this->prepareQuery($conditions, $relations, $select)->get();
    }

    public function updateOrCreate(array $find, array $data = []): mixed
    {
        return $this->model->updateOrCreate($find, $data);
    }

    public function firstOrCreate(array $data): mixed
    {
        return $this->model->firstOrCreate($data);
    }

    public function firstOrFail(array $conditions = [], array $relations = [], array $select = ['*']): Model|Builder
    {
        return $this->prepareQuery($conditions, $relations, $select)
            ->firstOrFail();
    }

    public function prepareQuery(array $conditions = [], array $relations = [], array $select = ['*']): Builder|\Illuminate\Database\Eloquent\Builder
    {
        return $this->model
            ->with($relations)
            ->where($conditions)
            ->select($select);
    }

    public function first(array $conditions = [], array $relations = [], array $select = ['*']): Model|Builder|null
    {
        return $this->prepareQuery($conditions, $relations, $select)->first();
    }

    public function retrieve(array $conditions = [], array $relations = [], array $select = ['*'], string $orderBy = 'id', string $scope = null): LengthAwarePaginator
    {
        $model = $scope ? $this->model->$scope() : $this->model;
        return $model->list()->filter()->with($relations)->where($conditions)->select($select)->orderBy($orderBy, 'DESC')->paginate(request('paginate') ?? config('paginate.count', env('PAGINATE_COUNT')));
    }

    public function update(array $data, array $conditions = [], array $select = ['*']): Model
    {
        $model = $this->model->where($conditions)->select($select)->firstOrFail();
        $model->update($data);
        return $model;
    }

    public function updateWhere(array $data, array $conditions = [])
    {
        $this->model->where($conditions)->update($data);
    }

    public function delete(array $conditions)
    {
        $this->model->where($conditions)->delete();
    }

    public function replicate(Model $model): Model
    {
        $duplicatedRecord = $model->replicate();
        $duplicatedRecord->save();
        return $duplicatedRecord;
    }

    public function getWhereIn(array $ids, array $conditions = [], string $selectedColumn = 'id', array $select = ['*'], array $relations = []): mixed
    {
        return $this->model->whereIn($selectedColumn, $ids)->where($conditions)->select($select)->with($relations)->get();
    }

    public function truncate()
    {
        $this->model->truncate();
    }

    public function takeRaws(int $nRaws, array $conditions = [], array $relations = [], array $select = ['*'], string $orderColumn = 'id', string $orderType = 'DESC'): Collection|array
    {
        return $this->prepareQuery($conditions, $relations, $select)
            ->orderBy($orderColumn, $orderType)
            ->take($nRaws)
            ->get();
    }
}
