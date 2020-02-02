<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Classes\RestApi;

class AuthController extends Controller
{
    protected $user;
    protected $response;


    public function __construct(UserRepository $user)
    {
        $this->user = $user;
        $this->user = $user;
    }

    public function login(UserLoginRequest $request)
    {
        if (!$user = $this->user->authorize($request->only(['email', 'password']))) {
            return RestApi::error('User not authorize.');
        }

        $token = $this->user->createAndSaveToken($user);

        $user = [
            'user' => new UserResource($user),
            'token' => $token
        ];

        return RestApi::successWithData($user);
    }

    public function register(UserRegisterRequest $request)
    {
        $user = $this->user->create([
            'first_name' => $request->get('first_name'),
            'last_name' => $request->get('last_name'),
            'email' => $request->get('email'),
            'password' => \Hash::make($request->get('password'))
        ]);

        event(new Registered($user));

        return RestApi::successWithData(new UserResource($user), "User Register successfully.");
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        dd($request->user());
        // $perPage = $request->get('limit', 10);

        $user = $this->user->where([])->paginate(10);


        return RestApi::setPagination($user)->success(UserResource::collection($user));
    }
}
