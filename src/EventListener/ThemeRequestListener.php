<?php

// see https://github.com/Sylius/SyliusThemeBundle/blob/master/docs/your_first_theme.md

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Sylius\Bundle\ThemeBundle\Context\SettableThemeContext;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;

class ThemeRequestListener
{
    /** @var ThemeRepositoryInterface */
    private $themeRepository;

    /** @var SettableThemeContext */
    private $themeContext;

    /** @var string|null */
    private $siteTheme;

    public function __construct(
        ThemeRepositoryInterface $themeRepository,
        SettableThemeContext $themeContext,
        ?string $siteTheme
    ) {
        $this->themeRepository = $themeRepository;
        $this->themeContext = $themeContext;
        $this->siteTheme = $siteTheme;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the main request
            return;
        }

        if (is_null($this->siteTheme)) {
            // go with the default theme
            return;
        }

        $theme = $this->themeRepository->findOneByName($this->siteTheme);
        if (!is_null($theme)) {
            $this->themeContext->setTheme($theme);
        }
    }
}
