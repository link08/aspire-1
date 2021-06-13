<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepositoryInterface;
use App\Utility\APIResponse;
use Illuminate\Http\Request;
use Auth;

class UserController extends Controller
{
    // Dependency
    private $userRepository;

    private $resource = "user";

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->userRepository->getById(Auth::user()->id);
        return APIResponse::transform($this->resource, $user);
    }

    /**
     * Register a new user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the input
        $userAttributes = $request->validate([
            'name'      => 'required|min:3',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:4'
        ]);

        $user = $this->userRepository->create($userAttributes);
        return APIResponse::transform($this->resource, $user);
    }

    /**
     * Display the specified authorized user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $user = $this->userRepository->getById($id);
        return APIResponse::transform($this->resource, $user);
    }

    /**
     * Update the specified authorized user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Implement when required
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Implement when required
    }
}
