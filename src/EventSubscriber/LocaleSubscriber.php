<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Priority 15: runs after RouterListener (32) and LocaleListener (16)
        // so session locale wins over the default.
        return [KernelEvents::REQUEST => [['onKernelRequest', 15]]];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }
        $locale = $request->getSession()->get('_locale');
        if ($locale) {
            $request->setLocale($locale);
        }
    }
}
