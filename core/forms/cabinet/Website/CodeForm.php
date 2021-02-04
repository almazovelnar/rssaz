<?php

namespace core\forms\cabinet\Website;

use Yii;
use yii\web\View;
use yii\base\Model;
use core\entities\Customer\Website\Code;

/**
 * Class CodeForm
 *
 * @package core\forms\cabinet
 */
class CodeForm extends Model
{
    private const CODE_DEFAULT = 'default';
    private const CODE_AMP = 'amp';

    public ?int $website = null;
    public int $blockCount = 5;
    public int $blockWidth = 190;
    public string $titleFont = 'Arial';
    public string $titleStyle = 'normal';
    public ?string $direction = null;
    public int $titleFontSize = 14;
    private ?Code $entity = null;

    public function __construct(?Code $code = null, array $config = [])
    {
        if ($code !== null) {
            $this->blockCount = $code->block_count;
            $this->blockWidth = $code->block_width;
            $this->titleFont = $code->title_font;
            $this->titleStyle = $code->title_style;
            $this->titleFontSize = $code->title_font_size;
            $this->direction = $code->direction;
            $this->entity = $code;
        }

        parent::__construct($config);
    }

    public function formName()
    {
        return '';
    }

    public function setWebsite($website): void
    {
        $this->website = intval($website);
    }

    public function getLimit(): int
    {
        return $this->entity->block_count ?? $this->blockCount;
    }

    public function applyStyles(): string
    {
        return "
            .custom-slider .slide-post {width: {$this->blockWidth}px;}
            .custom-slider .slide-post span {font-family: {$this->titleFont}; font-weight: {$this->titleStyle}; font-size: {$this->titleFontSize}px; }
        ";
    }

    public function attributeLabels(): array
    {
        return [
            'website' => Yii::t('code', 'choose_site'),
            'blockCount' => 'Blok sayı',
            'blockWidth' => 'Blokun eni (px)',
            'titleFont' => 'Başlığın fontu',
            'titleStyle' => 'Başlığın stili',
            'titleFontSize' => 'Başlığın font ölçüsü',
            'direction' => 'İstiqamət',
        ];
    }

    public function getCodeTypes(string $hash, bool $hasCode = false): array
    {
        $types = [
            self::CODE_DEFAULT => ['route' => '/code/get'],
            self::CODE_AMP => ['route' => '/code/get-amp'],
        ];

        foreach ($types as $type => $config) {
            $types[$type]['label'] = ucfirst($type);
            $types[$type]['content'] = $hasCode
                ? (new View())->render("/code/partials/{$type}-code", ['hash' => $hash, 'url' => Yii::$app->apiUrlManager
                    ->createAbsoluteUrl([$config['route'], 'hash' => $hash], true)])
                : null;
        }

        return $types;
    }

    public function rules(): array
    {
        return [
            [['website', 'blockCount', 'blockWidth', 'titleFont', 'titleStyle', 'titleFontSize', 'direction'], 'required'],
            [['website', 'blockCount', 'blockWidth', 'titleFontSize'], 'integer'],
        ];
    }
}