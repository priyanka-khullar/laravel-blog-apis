<?php

namespace App\Transformers;

use App\Models\Comment;
use App\Transformers\CommentTransformer;
use App\Transformers\UserTransformer;
use App\User;
use League\Fractal;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['comments','users'];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform($resource)
    {
        return [
            'id'                => $resource->id,
            'title'             => $resource->title,
            'short_description' => $resource->short_desc,
            'long_description'  => $resource->long_desc,
            'status'            => (boolean) $resource->status,
            'user_id'           => $resource->user_id		
        ];
    }

    public function includeComments($resource)
    {
        if(!$resource->comments) {
            return;
        }

        $transformer = new CommentTransformer;
        $type = 'comments';
        return $this->collection($resource->comments, $transformer, $type);
    }

    public function includeUsers($resource)
    {
        if(!$resource->user) {
            return;
        }

        $transformer = new UserTransformer;
        $transformer->setDefaultIncludes(null);
        $type = 'User';
        return $this->item($resource->user, $transformer, $type);
    }

}
