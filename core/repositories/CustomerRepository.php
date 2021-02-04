<?php

namespace core\repositories;

use core\entities\Customer\Customer;
use core\exceptions\NotFoundException;
use core\repositories\interfaces\UserRepositoryInterface;

class CustomerRepository extends AbstractRepository implements UserRepositoryInterface
{
    /**
     * @param $token
     * @return bool
     */
    public function existsByPasswordResetToken($token)
    {
        return (bool) Customer::find()->where(['password_reset_token' => $token, 'status' => Customer::STATUS_ACTIVE])->limit(1)->exists();
    }

    /**
     * @param $token
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByPasswordResetToken($token)
    {
        return $this->getBy(['password_reset_token' => $token, 'status' => Customer::STATUS_ACTIVE]);
    }

    /**
     * @param string $email
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByEmail(string $email)
    {
        return $this->getBy(['status' => Customer::STATUS_ACTIVE, 'email' => $email]);
    }

    /**
     * @param $q
     * @return array
     */
    public function getList($q)
    {
        $out = ['results' => ['id' => '', 'text' => '']];

        $customers = Customer::find()
            ->select(['id', "CONCAT(name, ' ', surname) AS text"])
            ->andWhere(['or', ['like', 'name', $q], ['like', 'surname', $q]])
            ->andWhere(['status' => Customer::STATUS_ACTIVE])
            ->limit(20)
            ->asArray()
            ->all();

        $out['results'] = array_values($customers);
        return $out;
    }

    public function all(string $q = null)
    {
        return Customer::find()
                    ->andWhere(['or', ['like', 'name', $q], ['like', 'surname', $q]])
                    ->andWhere(['status' => Customer::STATUS_ACTIVE])
                    ->all();
    }

    /**
     * @param $token
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByEmailConfirmToken($token)
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    /**
     * @param $username
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getByUsernameOrEmail($username)
    {
        return $this->getBy(['email' => $username]);
    }

    /**
     * @param array $condition
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundException
     */
    protected function getBy(array $condition)
    {
        if (!$user = Customer::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found!');
        }

        return $user;
    }
}