<?php

namespace App\Services;

use App\Repositories\UsersRepository;
use App\Services\BaseService;

class UsersService extends BaseService
{
	protected $repo;

	public function __construct(UsersRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * get all users
	 * @param  request objecr
	 * @return json response users list
	 */
	public function index($request)
	{
		return $this->repo->index($request);
	}

	/**
	 * view single user by id
	 * @param  request object
	 * @param  integer type id
	 * @return  json response single user
	 */
	public function show($request, $id)
	{
		return $this->repo->show($request, $id);
	}

	/**
	 * create user in user details table
	 * @param  set entity
	 * @return json response user 
	 */
	public function create($entity)
	{
		return $this->repo->create($entity);
	}

	/**
	 * update user
	 * @param  set entity
	 * @param  integer id
	 * @return json response user
	 */
	public function update($entity, $id)
	{
		return $this->repo->update($entity, $id);
	}

	public function delete($request, $id)
	{
		return $this->repo->delete($request, $id);
	}
}

