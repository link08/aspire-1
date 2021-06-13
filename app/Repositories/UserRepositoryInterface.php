<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
    /**
     * Create a User Model
     *
     * @param array $userAttributes
     * @return \App\Models\User
     */
    public function create(array $userAttributes);

    /**
     * Get All Users
     *
     * @param array $with
     * @return mixed
     */
    public function getAll(array $with);

    /**
     * Get User based on $id
     *
     * @param string $id
     * @param array $with
     * @return \App\Models\User
     */
    public function getById($id, array $with);

    /**
     * Update user for the given $id
     *
     * @param string $id
     * @param array $userAttributes
     * @return mixed
     */
    public function updateById($id, array $userAttributes);
}
