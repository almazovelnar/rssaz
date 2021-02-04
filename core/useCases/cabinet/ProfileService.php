<?php

namespace core\useCases\cabinet;

use core\components\Storage\directories\CustomerDirectory;
use core\entities\Customer\Customer;
use core\forms\cabinet\Profile\ChangePasswordForm;
use core\forms\cabinet\Profile\UpdateForm;
use core\repositories\CustomerRepository;

class ProfileService
{
    private $repository;
    private $directory;

    public function __construct(CustomerRepository $repository, CustomerDirectory $directory)
    {
        $this->repository = $repository;
        $this->directory = $directory;
    }

    public function update($id, UpdateForm $form)
    {
        /** @var Customer $customer */
        $customer = $this->repository->get($id);
        $customer->editProfile($form->name, $form->surname);
        if ($form->thumbFile) {
            $customer->addThumb($this->directory->save($form->thumbFile, $customer->thumb));
        }
        $this->repository->save($customer);
    }

    public function changePassword($id, ChangePasswordForm $form)
    {
        /** @var Customer $customer */
        $customer = $this->repository->get($id);
        $customer->setPassword($form->newPassword);
        $this->repository->save($customer);
    }
}