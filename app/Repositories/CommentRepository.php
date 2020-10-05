<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class CommentRepository extends BaseRepository
{
	protected $model;

	public function __construct(Comment $model)
	{
		$this->model = $model;
	}

	/**	
	 * add comment
	 * @param  set entity
	 * @return response comment
	 */
	public function create($entity)
	{
		try {
			$comment = new Comment;

			$comment->message 		= $entity->getMessage();
			$comment->user_id 		= $entity->getUserId();
			$comment->post_id 		= $entity->getPostId();
			$comment->save();

			return $comment;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**	
	 * get all comments
	 * @param  request obj
	 * @return comments list
	 */
	public function index(Request $request)
	{
		$comments = $this->model->paginate(10);
		return $comments;
	}

	/**	
	 * comment update
	 * @param  set entity
	 * @param  integer id
	 * @return response comment
	 */
	public function update($entity, $id)
	{
		try {
			$comment = Comment::findOrFail($id);

			$comment->message 		= $entity->getMessage();
			$comment->user_id 		= $entity->getUserId();
			$comment->post_id 		= $entity->getPostId();
			$comment->save();

			return $comment;
		} catch (Exception $e) {
			return $e->getMessage();	
		}
	}

	/**
	 * show single comment
	 * @param  Request obj
	 * @param integer id  
	 * @return  single comment return
	 * */
	public function show(Request $request, $id)
	{
		$comment = $this->model->find($id);
		return $comment;
	}

	public function delete(Request $request, $id)
	{
		$comment = Comment::find($id);

		if(!$comment) {
			return false;
		}

		$comment->delete();
		return true;
	}
}
