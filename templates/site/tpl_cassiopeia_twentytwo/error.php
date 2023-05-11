<?php

/**
 * @package     Joomla.Site
 * @subpackage  Templates.cassiopeia
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

/** @var Joomla\CMS\Document\ErrorDocument $this */

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

$this->setMetaData('viewport', 'width=device-width, initial-scale=1');

?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="metas" />
    <jdoc:include type="styles" />
    <jdoc:include type="scripts" />
</head>

<body class="site error_site <?php echo $option
    . ' ' . $wrapper
    . ' view-' . $view
    . ($layout ? ' layout-' . $layout : ' no-layout')
    . ($task ? ' task-' . $task : ' no-task')
    . ($itemid ? ' itemid-' . $itemid : '')
    . ' ' . $pageclass;
    echo ($this->direction == 'rtl' ? ' rtl' : '');
?>">
    <header class="header container-header full-width">
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
        <div class="grid-child container-component">
            <h1 class="page-header"><?php echo Text::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
            <div class="card">
                <div class="card-body">
                    <jdoc:include type="message" />
                    <p><strong><?php echo Text::_('JERROR_LAYOUT_ERROR_HAS_OCCURRED_WHILE_PROCESSING_YOUR_REQUEST'); ?></strong></p>
                    <p><?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></p>
                    <ul>
                        <li><?php echo Text::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
                        <li><?php echo Text::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
                    </ul>
                    <p><?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                    <p><a href="<?php echo $this->baseurl; ?>/index.php" class="btn btn-secondary"><span class="icon-home" aria-hidden="true"></span> <?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
                    <hr>
                    <p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                    <blockquote>
                        <span class="badge bg-secondary"><?php echo $this->error->getCode(); ?></span> <?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?>
                    </blockquote>
                    <?php if ($this->debug) : ?>
                        <div>
                            <?php echo $this->renderBacktrace(); ?>
                            <?php // Check if there are more Exceptions and render their data as well ?>
                            <?php if ($this->error->getPrevious()) : ?>
                                <?php $loop = true; ?>
                                <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
                                <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
                                <?php $this->setError($this->_error->getPrevious()); ?>
                                <?php while ($loop === true) : ?>
                                    <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                    <p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                                    <?php echo $this->renderBacktrace(); ?>
                                    <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                                <?php endwhile; ?>
                                <?php // Reset the main error object to the base error ?>
                                <?php $this->setError($this->error); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php if ($this->countModules('footer')) : ?>
    <footer class="container-footer footer full-width">
        <div class="grid-child">
            <jdoc:include type="modules" name="footer" style="none" />
        </div>
    </footer>
    <?php endif; ?>

    <jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
