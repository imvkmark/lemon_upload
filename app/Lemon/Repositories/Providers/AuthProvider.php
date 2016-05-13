<?php namespace App\Lemon\Repositories\Providers;

use App\Lemon\Repositories\Sour\LmUtil;

use App\Models\PamAccount;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class AuthProvider implements UserProvider {

	/**
	 * The Eloquent user model.
	 * @var string
	 */
	protected $model;

	/**
	 * Create a new database user provider.
	 * @param  string $model
	 */
	public function __construct($model) {
		$this->model = $model;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 * @param  mixed $identifier
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveById($identifier) {
		return $this->createModel()->newQuery()->find($identifier);
	}

	/**
	 * Retrieve a user by the given credentials.
	 * DO NOT TEST PASSWORD HERE!
	 * @param  array $credentials
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByCredentials(array $credentials) {

		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();

		foreach ($credentials as $key => $value) {
			if (!str_contains($key, 'password')) $query->where($key, $value);
		}

		return $query->first();
	}


	/**
	 * @param Authenticatable $user
	 * @param array           $credentials
	 * @return bool
	 */
	public function validateCredentials(Authenticatable $user, array $credentials) {
		$plain = $credentials['password'];
		/** @type PamAccount $user */
		return PamAccount::checkPassword($user, $plain);
	}


	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 * @param  mixed  $identifier
	 * @param  string $token
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	public function retrieveByToken($identifier, $token) {
		$model = $this->createModel();

		return $model->newQuery()
			->where($model->getKeyName(), $identifier)
			->where($model->getRememberTokenName(), $token)
			->first();
	}


	/**
	 * 更新记住的token
	 * @param Authenticatable $user
	 * @param string          $token
	 */
	public function updateRememberToken(Authenticatable $user, $token) {
		$user->setRememberToken($token);
		$user->logined_at = LmUtil::sqlTime();
		$user->save();
	}


	/**
	 * Create a new instance of the model.
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createModel() {
		$class = '\\' . ltrim($this->model, '\\');
		return new $class;
	}


}