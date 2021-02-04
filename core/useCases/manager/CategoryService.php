<?php

namespace core\useCases\manager;

use core\entities\Meta;
use core\entities\Category\Category;
use core\forms\manager\Category\Form;
use core\repositories\CategoryRepository;

class CategoryService
{
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Form $form)
    {
        $category = Category::create($form->name, $form->slug, $form->status, $form->showInMenu);
        $category->appendTo($this->repository->get($form->parentId));
        foreach ($form->translations as $translation) {
            $category->setVersion(
                $translation->language,
                $translation->title,
                new Meta($translation->meta->title, $translation->meta->description, $translation->meta->keywords)
            );
        }

        $this->repository->save($category);
    }

    public function edit($id, Form $form)
    {
        /** @var Category $category */
        $category = $this->repository->get($id);

        if (!$category->parentIdIsEqualTo($form->parentId)) {
            $category->appendTo($this->repository->get($form->parentId));
        }
        $category->edit($form->name, $form->slug, $form->status, $form->showInMenu);
        foreach ($form->translations as $translation) {
            $category->setVersion(
                $translation->language,
                $translation->title,
                new Meta($translation->meta->title, $translation->meta->description, $translation->meta->keywords)
            );
        }

        $this->repository->save($category);
    }

    public function moveUp(int $id)
    {
        /* @var Category $category */
        $category = $this->repository->get($id);

        if ($prev = $category->getPrev()->one()) {
            $category->insertBefore($prev);
        }

        $this->repository->save($category);
    }

    public function moveDown(int $id)
    {
        /* @var Category $category */
        $category = $this->repository->get($id);


        if ($next = $category->getNext()->one()) {
            $category->insertAfter($next);
        }

        $this->repository->save($category);
    }

    public function delete($id)
    {
        $category = $this->repository->get($id);
        $this->repository->remove($category);
    }
}