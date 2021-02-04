<?php

namespace core\useCases\cabinet;

use core\events\CodeGenerated;
use core\dispatchers\EventDispatcher;
use core\exceptions\NotFoundException;
use core\entities\Customer\Website\Code;
use core\forms\cabinet\Website\CodeForm;
use core\repositories\interfaces\CodeRepositoryInterface;

/**
 * Class CodeService
 * @package core\useCases\cabinet
 */
class CodeService
{
    private CodeRepositoryInterface $codeRepository;
    private EventDispatcher $eventDispatcher;

    public function __construct(
        CodeRepositoryInterface $codeRepository,
        EventDispatcher $eventDispatcher
    )
    {
        $this->codeRepository = $codeRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(CodeForm $form): Code
    {
        try {
            $code = $this->codeRepository->getByWebsite($form->website);
            $code->edit(
                $form->blockCount,
                $form->blockWidth,
                $form->direction,
                $form->titleFont,
                $form->titleStyle,
                $form->titleFontSize
            );
            $this->codeRepository->update($code->website_id, $code->getDirtyAttributes());
        } catch (NotFoundException $e) {
            $code = $this->create($form); // otherwise create record.
        } finally {
            $this->eventDispatcher->dispatch(new CodeGenerated($code));
            return $code;
        }
    }

    /**
     * @param CodeForm $form
     * @return Code
     */
    private function create(CodeForm $form): Code
    {
        return $this->codeRepository->save(Code::create(
            $form->website,
            $form->blockCount,
            $form->blockWidth,
            $form->direction,
            $form->titleFont,
            $form->titleStyle,
            $form->titleFontSize
        ));
    }
}