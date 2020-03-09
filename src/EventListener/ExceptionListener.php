<?php


namespace App\EventListener;

use App\Exceptions\PublicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Psr\Log\LoggerInterface;

class ExceptionListener
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    private $twig;

    /**
     * ExceptionListener constructor.
     * @param LoggerInterface $logger
     * @param ContainerInterface $container
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->twig = $container->get('twig');
    }

    public function onKernelException(ExceptionEvent $event)
    {

        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $response = new Response();

        if (!$exception instanceof PublicException) {
            $this->logger->error($message);
            $message = "Įvyko klaida";
        }

        $twig = $this->twig->render('bundles/TwigBundle/Exception/error.html.twig',
            ['message' => $message]);
        $response->setContent($twig);
        $event->setResponse($response);

    }
}
