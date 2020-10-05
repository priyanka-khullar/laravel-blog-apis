<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\User;
use Illuminate\Http\Request;

class UsersRepository extends BaseRepository
{
	protected $model;

	public function __construct(User $model)
	{
		$this->model = $model;
	}

	/**	
	 * get all users
	 * @param  request obj
	 * @return users list
	 */
	public function index(Request $request)
	{
		$users = User::with(['profiles'])->paginate(10);
		return $users;
	}

	/**
	 * show single user
	 * @param  Request obj
	 * @param integer id  
	 * @return  single user return
	 * */
	public function show(Request $request, $id)
	{
		$user = $this->model->find($id);
		return $user;
	}

	/**	
	 * user register
	 * @param  set entity
	 * @return response user
	 */
	public function create($entity)
	{
		try {

			$user = User::create([
				'name'		=>	$entity->getName(),
				'email'		=>	$entity->getEmail(),
				'password'	=>	bcrypt($entity->getPassword()),
				'role_id'	=> 	$entity->getRoleId(),
				'status'	=>	'1',
			]);

			$user->profiles()->create([
				'phone'		=>	$entity->getPhone(),
				'address'	=>	$entity->getAddress(),
				'user_id'	=>	$user->id,
				'image'		=> 	$entity->getImage(),
			]);

			return $user;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	/**	
	 * user update
	 * @param  set entity
	 * @param  integer id
	 * @return response user
	 */
	public function update($entity, $id)
	{
		try {
			$user = User::findOrFail($id);
			$fileName = $user->profiles->image;

			if($entity->getImage()) {
				$fileName = $entity->getImage();
			}

			$user->update([
				'name'		=>	$entity->getName(),
				'email'		=>	$entity->getEmail(),
				'password'	=>	bcrypt($entity->getPassword()),
				'role_id'	=> 	$entity->getRoleId(),
				'status'	=>	'1',
			]);

			$user->profiles()->update([
				'phone'		=>	$entity->getPhone(),
				'address'	=>	$entity->getAddress(),
				'user_id'	=>	$user->id,
				'image'		=> 	$fileName,
			]);

			return $user;
		} catch (Exception $e) {
			return $e->getMessage();	
		}
	}

	public function delete(Request $request, $id)
	{
		$user = User::find($id);

		if(!$user) {
			return false;
		}

		$user->delete();
		$user->profiles()->delete();
		return true;
	}
}