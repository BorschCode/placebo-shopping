<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ThemeExtension extends AbstractExtension
{
    public function __construct(private readonly Security $security) {}

    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_theme', $this->appTheme(...)),
        ];
    }

    public function appTheme(): string
    {
        $user = $this->security->getUser();
        return ($user instanceof User) ? $user->getTheme()->value : 'olx';
    }
}
