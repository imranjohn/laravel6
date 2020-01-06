<?php
namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepository;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserRepositoryModel extends BaseRepository implements UserRepository
{
    protected $model;
    
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function authorize(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            return Auth::getUser();
        }

        return false;
    }

    public function createAndSaveToken($user)
    {
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return $tokenResult->accessToken;
    }
}
