<?php

namespace core\repositories;

use core\repositories\interfaces\UserRepositoryInterface;
use core\entities\User;
use core\exceptions\NotFoundException;

/**
 * Class UserRepository
 *
 * @package core\repositories
 */
class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @param string $email
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByEmail(string $email)
    {
        return $this->getBy(['status' => true, 'email' => $email]);
    }

    /**
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByUsernameOrEmail($username)
    {
        return $this->getBy(['or', ['username' => $username], ['email' => $username]]);
    }

    /**
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundException
     */
    protected function getBy(array $condition)
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found!');
        }

        return $user;
    }
}