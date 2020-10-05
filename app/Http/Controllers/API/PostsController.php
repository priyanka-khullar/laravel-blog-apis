<?php

namespace App\Http\Controllers\API;

use App\Entities\PostEntity;
use App\Http\Controllers\API\ApiController;
use App\Http\Curl;
use App\Http\Requests\CreatePostRequest;
use App\Notifications\CreateUser;
use App\Services\PostService;
use App\Transformers\PostTransformer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use League\Fractal;

class PostsController extends ApiController
{
    protected $service;

    public function __construct(PostService $service)
    {
        $this->service = $service;
    }

    /**
     * view all posts from App\Models\Post table
     * @param  Object of request class
     * @return list of all posts
     */
    public function index(Request $request)
    {
        try {
            $fractal = new Fractal\Manager();

            // if get include parms then view relational data
            if (isset($request->include)) {
                $fractal->parseIncludes($_GET['include']);
            }

            $posts = $this->service->index($request);
            $transformer = new PostTransformer;
            $responseType = config('global.response.collections');

            return $this->respondCollection($posts, $transformer, $responseType);
        } catch (Exception $e) {
            return $this->getExceptionErrors($e);
        }
    }

    /**
     * add post
     * @param  request class object
     * @param  set entity of all request attributes
     * @return json response post
     */
    public function create(CreatePostRequest $request, PostEntity $entity)
    {
        try {
        	$entity->setTitle($request->title)
        			->setShortDesc($request->short_desc)
        			->setLongDesc($request->long_desc)
        			->setStatus($request->status)
        			->setUserId($this->getUserId());

        	$post = $this->service->create($entity);

            if($post) {
                Curl::sendSmsViaPostRequest();
                $post->notify(new CreateUser);
            }
        	$transformer = new PostTransformer;
    		$responseType = config('global.response.single_record');

    		return $this->respondItem($post, $transformer, $responseType, 'Post created successfully!');
        } catch (Exception $e) {
            return $this->getExceptionErrors($e);
        }
    }

    /**
     * show single post by id
     * @param  object of request class
     * @param  integer id
     * @return json response single post
     */
    public function show(Request $request, $id)
    {
        try {
            $post = $this->service->show($request, $id);

            if(!$post) {
                return $this->respondError('Post not found', 404);
            }
            $transformer = new PostTransformer;
            $responseType = config('global.response.single_record');

            return $this->respondItem($post, $transformer, $responseType);
        } catch (Exception $e) {
            return $this->getExceptionErrors($e);
        }
    }

    /**
     * update post by id
     * @param  object of CreatePostRequest class    
     * @param  object of postEntity class 
     * @param  integer type id     
     * @return json response updated post
     */
    public function update(Request $request, PostEntity $entity, $id)
    {
        if (Gate::forUser(\Auth::user())->allows('update-post', $id)) {
            try {
                $entity->setTitle($request->title)
                        ->setShortDesc($request->short_desc)
                        ->setLongDesc($request->long_desc)
                        ->setStatus($request->status)
                        ->setUserId($this->getUserId());

                $post = $this->service->update($entity, $id);
                $transformer = new PostTransformer;
                $responseType = config('global.response.single_record');

                return $this->respondItem($post, $transformer, $responseType, 'Post updated successfully!');
            } catch (Exception $e) {
                return $this->getExceptionErrors($e);
            }
        } else {
            return $this->respondError('You are not authorize for this action', 500);
        }
    }

    /**
     * delete post
     * @param  request class object
     * @param  integer id
     * @return json response success message
     */
    public function delete(Request $request, $id)
    {
        if (Gate::forUser(\Auth::user())->allows('delete-post', $id)) {
            try {
                $post = $this->service->delete($request, $id);

                if(!$post) {
                    return $this->respondError('Invalid Id', 422);
                }

                return $this->respondSuccess($post = [], 'Success', 200);
            } catch (Exception $e) {
                return $this->getExceptionErrors($e);
            }
        } else {
            return $this->respondError('You are not authorize for this action', 500);
        }
    }
}
