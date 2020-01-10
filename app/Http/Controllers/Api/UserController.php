<?php

namespace App\Http\Controllers\Api;

use App\Classes\RestApi;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = $this->user->where([])->paginate(request()->get('limit', 10));

        return RestApi::setPagination($users)->successWithData(UserResource::collection($users));
    }

    public function dashboard(Request $request){
        return RestApi::successWithData($request->user());
    }


    public function create(Request $request)
    {
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',

        ]);
        $data['password'] = '12345';

        $this->user->create($data);

        return RestApi::success("User created successfully..");
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $user = $this->user->getById($id);

        return RestApi::successWithData(new UserResource($user));
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $delete = $this->user->delete($id);
        return RestApi::success("User has been deleted successfully");
    }
}
