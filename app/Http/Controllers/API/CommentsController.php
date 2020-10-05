<?php

namespace App\Http\Controllers\API;

use App\Entities\CommentEntity;
use App\Http\Controllers\API\ApiController;
use App\Services\CommentService;
use App\Transformers\CommentTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use League\Fractal;

class CommentsController extends ApiController
{
	protected $service;

	public function __construct(CommentService $service)
	{
		$this->service = $service;
	}

	/**
	 * create comment in comments table
	 * @param  object of request class
	 * @param  object of entity class
	 * @return response add comment
	 */
	public function create(CommentEntity $entity, Request $request)
	{
		$entity->setMessage($request->message)
				->setUserId($this->getUserId())
				->setPostId($request->post_id);

		$user = $this->service->create($entity);
		$transformer = new CommentTransformer;
		$responseType = config('global.response.single_record');
		return $this->respondItem($user, $transformer, $responseType, 'Comment Inserted!');
	}
	/**
	 * get all comments
	 * @param  object of request class
	 * @return json response of all comments
	 */
	public function index(Request $request)
	{
		try {
			$fractal = new Fractal\Manager();

            if (isset($_GET['include'])) {
                $fractal->parseIncludes($_GET['include']);
            }
			$comments = $this->service->index($request);
            $transformer = new CommentTransformer;
            $responseType = config('global.response.collections');
            return $this->respondCollection($comments, $transformer, $responseType);
			
		} catch (Exception $e) {
			return $this->getExceptionErrors($e);			
		}
	}

	/**
	 * edit comment
	 * @param  object of Request class    
	 * @param  object of CommentEntity class 
	 * @param  integer type id     
	 * @return json response updated comment
	 */
	public function update(Request $request, CommentEntity $entity, $id)
	{
		if (Gate::forUser(\Auth::user())->allows('update-comment', $id)) {
			$entity->setMessage($request->message)
					->setUserId($this->getUserId())
					->setPostId($request->post_id);

			$comment = $this->service->update($entity, $id);

			$transformer = new CommentTransformer;
			$responseType = config('global.response.single_record');
			return $this->respondItem($comment, $transformer, $responseType, 'Comment Updated!');
		} else {
            return $this->respondError('You are not authorize for this action', 500);
        }
	}

	/**
	 * show single comment by id
	 * @param  object of request class
	 * @param  integer id
	 * @return json response single comment
	 */
	public function show(Request $request, $id)
	{
		$comment = $this->service->show($request, $id);

		if(!$comment) {
			return $this->respondError('Comment not found', 404);
		}

		$transformer = new CommentTransformer;
		$responseType = config('global.response.single_record');
		return $this->respondItem($comment, $transformer, $responseType);
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
