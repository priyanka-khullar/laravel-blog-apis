<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

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
        $host = request()->getSchemeAndHttpHost().'/uploads/';

        return [
            'id'        => $resource->id,
            'role_id'   => $resource->role_id,
            'name'      => $resource->name,
            'email'     => $resource->email,
            'phone'     => $resource->profiles->phone,
            'address'   => $resource->profiles->address,
            'status'    => (boolean) $resource->status,
            'profile'   => $host. 'larges/' . $resource->profiles->image,
            'thumbnail' => $host. 'thumbnails/' . $resource->profiles->image,
            'middle'    => $host. 'resize/' . $resource->profiles->image,
        ];
    }
}
