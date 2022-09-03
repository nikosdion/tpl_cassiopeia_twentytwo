<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\HtmlDocument $this */

JLoader::registerNamespace('\\Dionysopoulos\\Template\\Site\\TwentyTwo\\', __DIR__ . '/src');

$app = Factory::getApplication();
$wa  = $this->getWebAssetManager();

// Browsers support SVG favicons
$this->addHeadLink(
	HTMLHelper::_('image', 'logos/svg/icon-white-bg-red.svg', '', [], true, 1),
	'icon',
	'rel',
	['type' => 'image/svg+xml']
);
$this->addHeadLink(
	HTMLHelper::_('image', 'logos/webp/icon-white-bg-red@2x.webp', '', [], true, 1),
	'icon',
	'rel',
	['type' => 'image/webp']
);
$this->addHeadLink(
	HTMLHelper::_('image', 'logos/png/icon-white-bg-red@2x.png', '', [], true, 1),
	'icon',
	'rel',
	['type' => 'image/png']
);
$this->addHeadLink(
	HTMLHelper::_('image', 'logos/favicon/favicon-152.ico', '', [], true, 1),
	'alternate icon',
	'rel',
	['type' => 'image/vnd.microsoft.icon']
);

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
$menu     = $app->getMenu()->getActive();
$pageclass = $menu !== null ? $menu->getParams()->get('pageclass_sfx', '') : '';

// Enable assets
$wa->usePreset('template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr'))
    ->useStyle('template.active.language')
    ->useStyle('template.user')
    ->useScript('template.user');

// Override 'template.active' asset to set correct ltr/rtl dependency
$wa->registerStyle('template.active', '', [], [], ['template.cassiopeia.' . ($this->direction === 'rtl' ? 'rtl' : 'ltr')]);

$hasClass = '';

if ($this->countModules('sidebar-left', true)) {
    $hasClass .= ' has-sidebar-left';
}

if ($this->countModules('sidebar-right', true)) {
    $hasClass .= ' has-sidebar-right';
}

// Container
$wrapper = $this->params->get('fluidContainer') ? 'wrapper-fluid' : 'wrapper-static';

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

$stickyHeader = $this->params->get('stickyHeader') ? 'position-sticky sticky-top' : '';

// Defer fontawesome for increased performance. Once the page is loaded javascript changes it to a stylesheet.
$wa->getAsset('style', 'fontawesome')->setAttribute('rel', 'lazy-stylesheet');
?>
<!DOCTYPE html>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body class="site <?= $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ($pageclass ? ' ' . $pageclass : '')
    . $hasClass
    . ($this->direction == 'rtl' ? ' rtl' : '') ?>">
    <header class="header container-header full-width<?= $stickyHeader ? ' ' . $stickyHeader : '' ?>">

        <?php if ($this->countModules('topbar')) : ?>
            <div class="container-topbar">
            <jdoc:include type="modules" name="topbar" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('below-top')) : ?>
            <div class="grid-child container-below-top">
                <jdoc:include type="modules" name="below-top" style="none" />
            </div>
        <?php endif; ?>

		<div class="grid-child d-block d-lg-flex flex-row gap-3 align-items-center">
			<div class="navbar-brand">
				<a class="brand-logo d-flex flex-row gap-2 align-items-center text-decoration-none"
				   href="<?= $this->baseurl ?>/">
					<picture>
						<source srcset="<?= HTMLHelper::_('image', 'logos/svg/icon-white-bg.svg', '', [], true, 1) ?>">
						<source media="only screen and (min-device-pixel-ratio: 1.25)" srcset="<?= HTMLHelper::_('image', 'logos/webp/icon-white-bg-red@2x.webp', '', [], true, 1) ?>">
						<source srcset="<?= HTMLHelper::_('image', 'logos/webp/icon-white-bg-red.webp', '', [], true, 1) ?>">
						<source media="only screen and (min-device-pixel-ratio: 1.25)" srcset="<?= HTMLHelper::_('image', 'logos/png/icon-white-bg-red@2x.png', '', [], true, 1) ?>">
						<img src="<?= HTMLHelper::_('image', 'logos/png/icon-white-bg-red.png', '', [], true, 1) ?>"
							 alt="<?=  $sitename ?>"
							 class="img-fluid"
						>
					</picture>
					<h1 class="site-description fs-2 visually-hidden"><?= $sitename ?></h1>
				</a>
			</div>

			<?php if ($this->countModules('menu', true)) : ?>
				<div class="flex-grow-1">
					<jdoc:include type="modules" name="menu" style="none" />
				</div>
			<?php endif; ?>

			<?php if ($this->countModules('search', true)) : ?>
				<div class="container-search">
					<jdoc:include type="modules" name="search" style="none" />
				</div>
			<?php endif; ?>
		</div>
	</header>

    <div class="site-grid">
        <?php if ($this->countModules('banner', true)) : ?>
            <div class="container-banner full-width">
                <jdoc:include type="modules" name="banner" style="none" />
            </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-a', true)) : ?>
        <div class="grid-child container-top-a">
            <jdoc:include type="modules" name="top-a" style="card" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('top-b', true)) : ?>
        <div class="grid-child container-top-b">
            <jdoc:include type="modules" name="top-b" style="card" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('sidebar-left', true)) : ?>
        <div class="grid-child container-sidebar-left">
            <jdoc:include type="modules" name="sidebar-left" style="card" />
        </div>
        <?php endif; ?>

        <div class="grid-child container-component">
            <jdoc:include type="modules" name="breadcrumbs" style="none" />
            <jdoc:include type="modules" name="main-top" style="card" />
            <jdoc:include type="message" />
            <main>
            <jdoc:include type="component" />
            </main>
            <jdoc:include type="modules" name="main-bottom" style="card" />
        </div>

        <?php if ($this->countModules('sidebar-right', true)) : ?>
        <div class="grid-child container-sidebar-right">
            <jdoc:include type="modules" name="sidebar-right" style="card" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-a', true)) : ?>
        <div class="grid-child container-bottom-a">
            <jdoc:include type="modules" name="bottom-a" style="card" />
        </div>
        <?php endif; ?>

        <?php if ($this->countModules('bottom-b', true)) : ?>
        <div class="grid-child container-bottom-b">
            <jdoc:include type="modules" name="bottom-b" style="card" />
        </div>
        <?php endif; ?>
    </div>

    <?php if ($this->countModules('footer', true)) : ?>
    <footer class="container-footer footer full-width">
        <div class="grid-child">
            <jdoc:include type="modules" name="footer" style="none" />
        </div>
    </footer>
    <?php endif; ?>

    <?php if ($this->params->get('backTop') == 1) : ?>
        <a href="#top" id="back-top" class="back-to-top-link" aria-label="<?= Text::_('TPL_CASSIOPEIA_BACKTOTOP') ?>">
            <span class="icon-arrow-up icon-fw" aria-hidden="true"></span>
        </a>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
