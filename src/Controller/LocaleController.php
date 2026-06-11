<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LocaleController extends AbstractController
{
    private const ALLOWED = ['uk', 'en', 'ka', 'pl'];

    #[Route('/locale/{locale}', name: 'app_switch_locale', requirements: ['locale' => 'uk|en|ka|pl'])]
    public function switch(Request $request, string $locale): Response
    {
        if (in_array($locale, self::ALLOWED, true)) {
            $request->getSession()->set('_locale', $locale);
        }

        $referer = $request->headers->get('referer', '');
        $host = $request->getSchemeAndHttpHost();
        $safe = $referer && str_starts_with($referer, $host) ? $referer : '/';

        return $this->redirect($safe);
    }
}
