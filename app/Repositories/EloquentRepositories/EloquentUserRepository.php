<?php

namespace App\Repositories\EloquentRepositories;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Auth;
use Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    /**
     * Create & save a User Model
     *
     * @param array $userAttributes
     * @return null
     */
    public function create($userAttributes)
    {
        // Create a new user modal and save it
        $user = new User;
        $user->password = Hash::make($userAttributes['password']);
        $user->email    = $userAttributes['email'];
        $user->name     = $userAttributes['name'];
        if($user->save()) {
            return $user->toArray();
        }
    }

    /**
     * Get All Users
     *
     * @param array $with
     * @return mixed
     */
    public function getAll($with = [])
    {
        // Implement when required
    }

    /**
     * Get authorized User based on $id
     *
     * @param string $id
     * @return @return \App\Models\User
     */
    public function getById($id, $with=[])
    {
        $user = User::where('id', Auth::user()->id)->find($id);
        return $user;
    }

    /**
     * Update user for the given $id
     *
     * @param string $id
     * @return mixed
     */
    public function updateById($id, $userAttributes)
    {
        // Implement when required
    }
}
