<?php

namespace core\useCases\manager;

use core\components\Storage\directories\CustomerDirectory;
use core\entities\Customer\Customer;
use core\forms\manager\Customer\CreateForm;
use core\forms\manager\Customer\UpdateForm;
use core\forms\manager\PasswordUpdateForm;
use core\repositories\CustomerRepository;

class CustomerService
{
    private $repository;
    private $directory;

    public function __construct(CustomerRepository $repository, CustomerDirectory $directory)
    {
        $this->directory = $directory;
        $this->repository = $repository;
    }

    public function create(CreateForm $form)
    {
        $customer = Customer::create($form->name, $form->surname, $form->email, $form->password, $form->status);
        if ($form->thumbFile) {
            $customer->addThumb($this->directory->save($form->thumbFile));
        }
        $this->repository->save($customer);
    }

    public function edit($id, UpdateForm $form)
    {
        /** @var Customer $customer */
        $customer = $this->repository->get($id);
        $customer->edit($form->name, $form->surname, $form->email, $form->status);
        if ($form->thumbFile) {
            $customer->addThumb($this->directory->save($form->thumbFile, $customer->thumb));
        }
        $this->repository->save($customer);
    }

    public function updatePassword($id, PasswordUpdateForm $form)
    {
        /** @var Customer $customer */
        $customer = $this->repository->get($id);
        $customer->setPassword($form->newPassword);
        $this->repository->save($customer);
    }
}