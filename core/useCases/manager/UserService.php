<?php

namespace core\useCases\manager;

use core\entities\User;
use core\forms\manager\PasswordUpdateForm;
use core\repositories\UserRepository;
use core\forms\manager\User\{CreateForm, UpdateForm};

/**
 * Class UserService
 *
 * @package core\useCases
 */
class UserService
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UserService constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateForm $form
     * @return User
     */
    public function create(CreateForm $form): User
    {
        $user = User::create(
            $form->email,
            $form->username,
            $form->password,
            $form->role,
            (bool) $form->status
        );

        $this->repository->save($user);

        return $user;
    }

    /**
     * @param int $id
     * @param UpdateForm $form
     */
    public function update(int $id, UpdateForm $form): void
    {
        /** @var User $user */
        $user = $this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email,
            $form->role,
            (bool) $form->status
        );

        $this->repository->save($user);
    }

    /**
     * @param int $id
     * @param \core\forms\manager\PasswordUpdateForm $form
     */
    public function updatePassword(int $id, PasswordUpdateForm $form)
    {
        /** @var User $user */
        $user = $this->repository->get($id);
        $user->setPassword($form->newPassword);
        $this->repository->save($user);
    }

    /**
     * @param int $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        /** @var User $user */
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }
}