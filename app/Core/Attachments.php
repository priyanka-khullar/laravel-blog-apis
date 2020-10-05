<?php

namespace App\Core;

use Intervention\Image\Facades\Image;

class Attachments
{
	protected $file;
	protected $uploadImage;

	public function __construct($file)
	{
		$this->file = $file;
	}


	public function getOriginal()
	{
		$folderPath = 'uploads/images/';
		$extension = $this->file->getClientOriginalExtension();
		$fileName = time().'.'.$extension;
		$this->file->move($folderPath, $fileName);
		$this->uploadImage = $folderPath . $fileName;

		return $this->fileName = $fileName;
	}

	public function generateLarge()
	{
		$folderPath = 'uploads/larges/';
		$extension = $this->file->getClientOriginalExtension();
		$fileName = $this->getFileName();

		Image::make($this->uploadImage)->resize(800, 600)
				->save($folderPath . $fileName);
		
		return $fileName;
	}

	public function generateThumbnail()
	{
		$folderPath = 'uploads/thumbnails/';
		$extension = $this->file->getClientOriginalExtension();
		$fileName = $this->getFileName();

		Image::make($this->uploadImage)->resize(60, 60)
				->save($folderPath . $fileName);
		
		return $fileName;
	}

	public function generateMedium()
	{
		$folderPath = 'uploads/resize/';
		$extension = $this->file->getClientOriginalExtension();
		$fileName = $this->getFileName();
		
		Image::make($this->uploadImage)->resize(200, 200)
				->save($folderPath . $fileName);

		return $fileName;
	}

	public function getFileName()
	{
		return $this->fileName;
	}
}
