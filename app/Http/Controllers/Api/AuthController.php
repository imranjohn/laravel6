<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Classes\RestApi;

class AuthController extends Controller
{
    protected $user;
    protected $response;


    public function __construct(UserRepository $user)
    {
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
        
        return RestApi::successWithData(new UserResource($user), "User Register successfully.");
    }


    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        // $user = new User([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password)
        // ]);
        // $user->save();
        // return response()->json([
        //     'message' => 'Successfully created user!'
        // ], 201);
    }

    // /**
    //  * Login user and create token
    //  *
    //  * @param  [string] email
    //  * @param  [string] password
    //  * @param  [boolean] remember_me
    //  * @return [string] access_token
    //  * @return [string] token_type
    //  * @return [string] expires_at
    //  */
    // public function login(Request $request)
    // {

    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //         'remember_me' => 'boolean'
    //     ]);

    //     $credentials = request(['email', 'password']);
    //     if(!Auth::attempt($credentials))
    //         return response()->json([
    //             'message' => 'Unauthorized'
    //         ], 401);
    //     $user = $request->user();
    //     $tokenResult = $user->createToken('Personal Access Token');
    //     $token = $tokenResult->token;
    //     if ($request->remember_me)
    //         $token->expires_at = Carbon::now()->addWeeks(1);
    //     $token->save();
    //     return response()->json([
    //         'access_token' => $tokenResult->accessToken,
    //         'token_type' => 'Bearer',
    //         'expires_at' => Carbon::parse(
    //             $tokenResult->token->expires_at
    //         )->toDateTimeString()
    //     ]);
    // }

    // /**
    //  * Logout user (Revoke the token)
    //  *
    //  * @return [string] message
    //  */
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
