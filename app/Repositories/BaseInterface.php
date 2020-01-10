<?php
namespace App\Repositories;
interface BaseInterface {

    public function getById($id);

    public function all();

    public function create($data = []);

    public function update($data=[], $id);

    public function delete($id);

    public function paginate($perPage = 10);

    public function where($condition = []);
}
