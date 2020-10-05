<?php

namespace App\Services;

use App\Repositories\CommentRepository;
use App\Services\BaseService;

class CommentService extends BaseService
{
	protected $repo;

	public function __construct(CommentRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * create comment in comment details table
	 * @param  set entity
	 * @return json response comment 
	 */
	public function create($entity)
	{
		return $this->repo->create($entity);
	}

	/**
	 * get all comments
	 * @param  request objecr
	 * @return json response comments list
	 */
	public function index($request)
	{
		return $this->repo->index($request);
	}

	/**
	 * update comment
	 * @param  set entity
	 * @param  integer id
	 * @return json response comment
	 */
	public function update($entity, $id)
	{
		return $this->repo->update($entity, $id);
	}

	/**
	 * view single comment by id
	 * @param  request object
	 * @param  integer type id
	 * @return  json response single comment
	 */
	public function show($request, $id)
	{
		return $this->repo->show($request, $id);
	}

	public function delete($request, $id)
	{
		return $this->repo->delete($request, $id);
	}
}
