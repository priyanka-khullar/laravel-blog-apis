<?php

namespace App\Services;

use App\Repositories\PostRepository;
use App\Services\BaseService;

class PostService extends BaseService
{
	protected $repo;

	public function __construct(PostRepository $repo)
	{
		$this->repo = $repo;
	}

	/**
	 * get all posts
	 * @param  request object
	 * @return json response posts list
	 */
	public function index($request)
	{
		return $this->repo->index($request);
	}

	public function create($entity)
	{
		return $this->repo->create($entity);
	}

	/**
	 * view single post by id
	 * @param  request object
	 * @param  integer type id
	 * @return  json response single post
	 */
	public function show($request, $id)
	{
		return $this->repo->show($request, $id);
	}

	/**
	 * update post
	 * @param  set entity
	 * @param  integer id
	 * @return json response post
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

