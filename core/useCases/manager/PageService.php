<?php

namespace core\useCases\manager;

use core\entities\Meta;
use core\entities\Page\Page;
use core\forms\manager\Page\Form;
use core\repositories\PageRepository;

/**
 * Class PageService
 *
 * @package core\useCases
 */
class PageService
{
    /**
     * @var PageRepository
     */
    private $repository;

    /**
     * PageService constructor.
     * @param PageRepository $repository
     */
    public function __construct(PageRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * @param Form $form
     */
    public function create(Form $form)
    {
        $page = Page::create($form->slug, $form->status, $form->show, $form->type);

        foreach ($form->translations as $translation) {
            $page->setVersion(
                $translation->language,
                $translation->title,
                $translation->description,
                new Meta(
                    $translation->meta->title,
                    $translation->meta->description,
                    $translation->meta->keywords
                )
            );
        }

        $this->repository->save($page);
    }

    /**
     * @param Page $page
     * @param Form $form
     */
    public function update(Page $page, Form $form)
    {
        $page->edit($form->slug, $form->status, $form->show, $form->type);

        foreach ($form->translations as $translation) {
            $page->setVersion(
                $translation->language,
                $translation->title,
                $translation->description,
                new Meta(
                    $translation->meta->title,
                    $translation->meta->description,
                    $translation->meta->keywords
                )
            );
        }

        $this->repository->save($page);
    }

    /**
     * @param int $id
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function delete(int $id)
    {
        $this->repository->remove(
            $this->repository->get($id)
        );
    }

}