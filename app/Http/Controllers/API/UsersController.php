<?php

namespace App\Http\Controllers\API;

use App\Core\Attachments;
use App\Entities\UserEntity;
use App\Http\Controllers\API\ApiController;
use App\Http\Requests\CreateUserRequest;
use App\Notifications\CreateUser;
use App\Services\UsersService;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends ApiController
{
	protected $service;
	protected $userTransformer;

	public function __construct(UsersService $service, 
		UserTransformer $userTransformer)
	{
		$this->service = $service;
		$this->userTransformer = $userTransformer;
	}

	/**
	 * create users in user_details table
	 * @param  object of CreateUserRequest class
	 * @param  object of entity UserEntity class
	 * @return response registered user
	 */
	public function create(CreateUserRequest $request, UserEntity $entity)
	{
		$fileName = null;
		if($request->profile) {
			$attachments = new Attachments($request->profile);
			$attachments->getOriginal();
			$attachments->generateLarge();
			$attachments->generateThumbnail();
			$attachments->generateMedium();
			$fileName = $attachments->getFileName();
		}

		$entity->setName($request->name)
				->setEmail($request->email)
				->setPassword($request->password)
				->setPhone($request->phone)
				->setRoleId($request->role_id)
				->setAddress($request->address)
				->setImage($fileName)
				->setStatus($this->getUserStatus());

		$user = $this->service->create($entity);
		if($user) {
			$user->notify(new CreateUser);
		}
		$transformer = new UserTransformer;
		$responseType = config('global.response.single_record');
		return $this->respondItem($user, $transformer, $responseType, 'User Registered!');
	}

	/**
	 * get all users in user details table
	 * @param  object of request class
	 * @return json response of all users
	 */
	public function index(Request $request)
	{
		try {
			$users = $this->service->index($request);
            $transformer = new UserTransformer;
            $responseType = config('global.response.collections');
            return $this->respondCollection($users, $transformer, $responseType);
			
		} catch (Exception $e) {
			return $this->getExceptionErrors($e);			
		}
	}

	/**
	 * show single user by id
	 * @param  object of request class
	 * @param  integer id
	 * @return json response single user
	 */
	public function show(Request $request, $id)
	{
		$user = $this->service->show($request, $id);

		if(!$user) {
			return $this->respondError('User not found', 404);
		}

		$transformer = new UserTransformer;
		$responseType = config('global.response.single_record');
		return $this->respondItem($user, $transformer, $responseType);
	}

	/**
	 * update user by id
	 * @param  object of Request class    
	 * @param  object of UserEntity class 
	 * @param  integer type id     
	 * @return json response updated user
	 */
	public function update(Request $request, UserEntity $entity, $id)
	{
		$fileName = null;
		if($request->profile) {
			$attachments = new Attachments($request->profile);
			$attachments->getOriginal();
			$attachments->generateLarge();
			$attachments->generateThumbnail();
			$attachments->generateMedium();
			$fileName = $attachments->getFileName();
		}

		$entity->setName($request->name)
				->setEmail($request->email)
				->setPassword($request->password)
				->setPhone($request->phone)
				->setRoleId($request->role_id)
				->setAddress($request->address)
				->setImage($fileName)
				->setStatus($this->getUserStatus());

		$user = $this->service->update($entity, $id);

		$transformer = new UserTransformer;
		$responseType = config('global.response.single_record');
		return $this->respondItem($user, $transformer, $responseType, 'User Updated!');
	}

	public function delete(Request $request, $id)
    {
        try {
            $user = $this->service->delete($request, $id);

            if(!$user) {
                return $this->respondError('Invalid Id', 422);
            }
            
            return $this->respondSuccess($user = [], 'Success', 200);
        } catch (Exception $e) {
            return $this->getExceptionErrors($e);
        }
    }
}
