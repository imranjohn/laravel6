<?php
namespace App\Repositories;

abstract class BaseRepository
{
    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function create($data = [])
    {
        return $this->model->create($data);
    }

    public function update($data=[], $id)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function paginate($perPage = 10)
    {
        return $this->model->paginate($perPage);
    }

    public function where($condition = [])
    {
        return $this->model->where($condition);
    }
}
