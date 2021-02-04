<?php

namespace common\bootstrap;

use Yii;
use DOMDocument;
use yii\queue\Queue;
use yii\base\Application;
use yii\mail\MailerInterface;
use yii\caching\CacheInterface;
use yii\base\BootstrapInterface;
use core\validators\DOMValidator;
use yii\di\{Instance, Container};
use Elasticsearch\{ClientBuilder};
use GuzzleHttp\{Client, ClientInterface};
use core\repositories\{
    CategoryRepository,
    CodeRepository,
    interfaces\CodeRepositoryInterface,
    ImageRepository,
    interfaces\ImageRepositoryInterface,
    interfaces\PostDuplicateReasonRepositoryInterface,
    interfaces\WhiteListedDomainRepositoryInterface,
    PostDuplicateReasonRepository,
    PostRepository,
    interfaces\PostRepositoryInterface,
    SessionRepository,
    interfaces\SessionRepositoryInterface,
    WebsiteRepository,
    interfaces\WebsiteRepositoryInterface,
    RssRepository,
    interfaces\RssRepositoryInterface,
    BlockedDomainRepository,
    interfaces\BlockedDomainRepositoryInterface,
    AlgorithmRepository,
    interfaces\AlgorithmRepositoryInterface,
    WhiteListedDomainRepository};
use core\events\{RssParseFinished,
    View,
    Click,
    OwnClick,
    CodeGenerated,
    WebsiteUpdated,
    ExistingPostDetected,
    ParseFinished,
    RssParseErrorDetected};
use core\listeners\{ClickListener,
    OwnClickListener,
    ParseFinishedDuplicatesListener,
    ViewListener,
    CodeGeneratedListener,
    WebsiteUpdatedListener,
    ParseFinishedDiagnosticsListener,
    ExistingPostDetectedListener,
    ParseFinishedSearchListener,
    RssParseErrorDetectedListener,
    PasswordResetRequestListener,
    SignUpRequestListener};
use core\dispatchers\{EventDispatcher, AsyncEventDispatcher, DeferredEventDispatcher, SimpleEventDispatcher};
use core\jobs\AsyncEventJobHandler;
use core\components\Storage\directories\PostDirectory;
use core\services\parser\{RssParser, ParserDto, EntryAnalyzer};
use core\useCases\events\{SignUpRequested, PasswordResetRequested};
use Jenssegers\ImageHash\{ImageHash, Implementations\PerceptualHash};

/**
 * Class SetUp
 * @package common\bootstrap
 */
class SetUp implements BootstrapInterface
{
    /**
     * @param Application $app
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $this->registerComponents($container, $app);

        $this->registerRepositories($container);

        $this->registerEventDispatchers($container);

        $this->registerEvents($container);
    }

    public function registerComponents(Container $container, Application $app)
    {
        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(CacheInterface::class, function () use ($app) {
            return $app->cache;
        });

        $container->setSingleton(\Elasticsearch\Client::class, function () use ($app) {
            $client = ClientBuilder::create();
            if (isset($app->params['elasticHost']))
                $client->setHosts([$app->params['elasticHost']]);
            return $client->build();
        });

        $container->setSingleton(Queue::class, fn () => $app->get('queue'));
        $container->setSingleton(ClientInterface::class, Client::class);
        $container->setSingleton(DOMValidator::class, fn () => new DOMValidator(new DOMDocument('1.0', 'utf-8'), new Client(['timeout' => 10])));

        $container->setSingleton(EntryAnalyzer::class, function (Container $container) {
            /** @var ImageRepository $imageRepository */
            /** @var PostRepositoryInterface $postRepository */
            $imageRepository = $container->get(ImageRepositoryInterface::class);
            $postRepository = $container->get(PostRepositoryInterface::class);
            return new EntryAnalyzer(new ImageHash(new PerceptualHash()), $imageRepository, $postRepository);
        });

        $container->setSingleton(RssParser::class, function (Container $container) {
            /** @var PostDuplicateReasonRepositoryInterface $duplicateReasonRepository */
            /** @var PostRepositoryInterface $postRepository */
            /** @var ImageRepositoryInterface $imageRepository */
            /** @var CategoryRepository $categoryRepository */
            /** @var EventDispatcher $eventDispatcher */
            /** @var PostDirectory $postDirectory */
            /** @var EntryAnalyzer $entryAnalyzer */
            /** @var DOMValidator $domValidator */
            /** @var ParserDto $parserDto */

            $duplicateReasonRepository = $container->get(PostDuplicateReasonRepositoryInterface::class);
            $postRepository = $container->get(PostRepositoryInterface::class);
            $imageRepository = $container->get(ImageRepositoryInterface::class);
            $categoryRepository = $container->get(CategoryRepository::class);
            $eventDispatcher = $container->get(EventDispatcher::class);
            $postDirectory = $container->get(PostDirectory::class);
            $entryAnalyzer = $container->get(EntryAnalyzer::class);
            $domValidator = $container->get(DOMValidator::class);
            $parserDto = $container->get(ParserDto::class);

            return new RssParser(
                $duplicateReasonRepository,
                $postRepository,
                $imageRepository,
                $categoryRepository,
                $eventDispatcher,
                $postDirectory,
                $entryAnalyzer,
                $domValidator,
                $parserDto
            );
        });
    }

    public function registerRepositories(Container $container)
    {
        $repositoryMap = [
            WebsiteRepositoryInterface::class => WebsiteRepository::class,
            CodeRepositoryInterface::class => CodeRepository::class,
            SessionRepositoryInterface::class => SessionRepository::class,
            PostRepositoryInterface::class => PostRepository::class,
            RssRepositoryInterface::class => RssRepository::class,
            BlockedDomainRepositoryInterface::class => BlockedDomainRepository::class,
            WhiteListedDomainRepositoryInterface::class => WhiteListedDomainRepository::class,
            AlgorithmRepositoryInterface::class => AlgorithmRepository::class,
            ImageRepositoryInterface::class => ImageRepository::class,
            PostDuplicateReasonRepositoryInterface::class => PostDuplicateReasonRepository::class,
        ];

        foreach ($repositoryMap as $abstract => $mappedValue) {
            $container->setSingleton($abstract, $mappedValue);
        }
    }

    public function registerEventDispatchers(Container $container)
    {
        $container->setSingleton(DeferredEventDispatcher::class, function (Container $container) {
            /** @var Queue $queue */
            $queue = $container->get(Queue::class);
            return new DeferredEventDispatcher(new AsyncEventDispatcher($queue));
        });

        $container->setSingleton(AsyncEventJobHandler::class, [], [
            Instance::of(SimpleEventDispatcher::class)
        ]);

        $container->setSingleton(EventDispatcher::class, DeferredEventDispatcher::class);
    }

    public function registerEvents(Container $container)
    {
        $container->setSingleton(SimpleEventDispatcher::class, function (Container $container) {
            return new SimpleEventDispatcher($container, [
                SignUpRequested::class => [SignUpRequestListener::class],
                PasswordResetRequested::class => [PasswordResetRequestListener::class],
                RssParseErrorDetected::class => [RssParseErrorDetectedListener::class],
                // RssParseItemErrorDetected::class => [RssParseItemErrorDetectedListener::class],
                RssParseFinished::class => [ParseFinishedDiagnosticsListener::class],
                ParseFinished::class => [
                    ParseFinishedSearchListener::class,
                    ParseFinishedDuplicatesListener::class
                ],
                // ExistingPostDetected::class => [ExistingPostDetectedListener::class],
                CodeGenerated::class => [CodeGeneratedListener::class],
                WebsiteUpdated::class => [WebsiteUpdatedListener::class],
                View::class => [ViewListener::class],
                Click::class => [ClickListener::class],
                OwnClick::class => [OwnClickListener::class],
            ]);
        });
    }
}