<?php

namespace Silecust\Framework\Service\Component\Controller;

use Silecust\Framework\Service\Twig\TwigConstants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EnhancedAbstractController extends AbstractController
{
    const string TWIG_PRE_RENDER_EVENT = "silecust.twig.pre_render";

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {

    }

    public function render($view, array $parameters = [], ?Response $response = null): Response
    {

        $event = new TwigPreRenderEvent($view, $parameters, $response);

        $this->eventDispatcher->dispatch($event, self::TWIG_PRE_RENDER_EVENT);

        return parent::render($event->getView(), $event->getParameters(), $event->getResponse());
    }

    public function setContentHeading(Request $request, string $contentHeading): void
    {
        $request->attributes->set(TwigConstants::UI_CONTENT_HEADING, $contentHeading);

    }
}