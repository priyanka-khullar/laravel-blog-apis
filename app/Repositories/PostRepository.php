<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class PostRepository extends BaseRepository
{
	protected $model;

	public function __construct(Post $model)
	{
		$this->model = $model;
	}

	/**	
	 * get all posts
	 * @param  request obj
	 * @return posts list
	 */
	public function index(Request $request)
	{
		$posts = $this->model->paginate();

		if (isset($request->include)) {
            $posts = Post::with(['comments','user'])->paginate();
        }

		return $posts;
	}

	public function create($entity)
	{
		try {
			$post = new Post;

			$post->title 		= $entity->getTitle();
			$post->short_desc	= $entity->getShortDesc();
			$post->long_desc	= $entity->getLongDesc();
			$post->status 		= $entity->getStatus();
			$post->user_id 		= $entity->getUserId();
			$post->save();

			return $post;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * show single post
	 * @param  Request obj
	 * @param integer id  
	 * @return  single post return
	 * */
	public function show(Request $request, $id)
	{
		$post = $this->model->find($id);
		return $post;
	}

	/**	
	 * post update
	 * @param  set entity
	 * @param  integer id
	 * @return response post
	 */
	public function update($entity, $id)
	{
		try {
			$post = Post::findOrFail($id);

			$post->title 		= $entity->getTitle();
			$post->short_desc	= $entity->getShortDesc();
			$post->long_desc	= $entity->getLongDesc();
			$post->status 		= $entity->getStatus();
			$post->user_id 		= $entity->getUserId();
			$post->save();

			return $post;
		} catch (Exception $e) {
			return $e->getMessage();	
		}
	}

	public function delete(Request $request, $id)
	{
		$post = Post::find($id);

		if(!$post) {
			return false;
		}

		$post->delete();
		return true;
	}
}
