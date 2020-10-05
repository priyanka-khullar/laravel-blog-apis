<?php

namespace App\Transformers;

use App\Transformers\UserDetailsTransformer;
use League\Fractal;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['users'];

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
            'id'        => $resource->id,
			'message'   => $resource->message,
            'status'    => (boolean) $resource->status,
            'post_id'   => $resource->post_id,
        ];
    }

    public function includeUsers($resource)
    {
        if(!$resource->user) {
            return;
        }

        $transformer = new UserDetailsTransformer;
        $transformer->setDefaultIncludes(null);
        $type = 'Users';
        return $this->item($resource->user, $transformer, $type);
    }
}
