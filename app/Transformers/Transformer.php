<?php

namespace App\Transformers;

abstract class Transformer
{
	public function transformerCollection($items)
	{
		return array_map([$this, 'transform'], $items->toArray());
	}

	public abstract function transform($item);
}
