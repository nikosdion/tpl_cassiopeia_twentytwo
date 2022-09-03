<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2017 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$blockPosition = $displayData['params']->get('info_block_position', 0);

?>
<dl class="article-info text-muted d-block d-sm-flex gap-4">

    <?php if (
    $displayData['position'] === 'above' && ($blockPosition == 0 || $blockPosition == 2)
            || $displayData['position'] === 'below' && ($blockPosition == 1)
) : ?>
        <dt class="article-info-term visually-hidden">
            <?php if ($displayData['params']->get('info_block_show_title', 1)) : ?>
                <?php echo Text::_('COM_CONTENT_ARTICLE_INFO'); ?>
            <?php endif; ?>
        </dt>

        <?php if ($displayData['params']->get('show_author') && !empty($displayData['item']->author)) : ?>
            <?php echo $this->sublayout('author', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_parent_category') && !empty($displayData['item']->parent_id)) : ?>
            <?php echo $this->sublayout('parent_category', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_category')) : ?>
            <?php echo $this->sublayout('category', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_associations')) : ?>
            <?php echo $this->sublayout('associations', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_publish_date')) : ?>
            <?php echo $this->sublayout('publish_date', $displayData); ?>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (
    $displayData['position'] === 'above' && ($blockPosition == 0)
            || $displayData['position'] === 'below' && ($blockPosition == 1 || $blockPosition == 2)
) : ?>
        <?php if ($displayData['params']->get('show_create_date')) : ?>
            <?php echo $this->sublayout('create_date', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_modify_date')) : ?>
            <?php echo $this->sublayout('modify_date', $displayData); ?>
        <?php endif; ?>

        <?php if ($displayData['params']->get('show_hits')) : ?>
            <?php echo $this->sublayout('hits', $displayData); ?>
        <?php endif; ?>
    <?php endif; ?>
</dl>

<meta itemprop="headline" content="<?= htmlentities($this->escape($displayData['item']->title)) ?>">
<?php if (!empty($images)): ?>
	<meta itemprop="image" content="<?= implode(',', array_map(function ($url) {
		return htmlentities($url);
	}, $images)) ?>">
<?php endif; ?>
<meta itemprop="dateModified" content="<?= HTMLHelper::_('date', $displayData['item']->modified, 'c') ?>">
<meta itemprop="mainEntityOfPage" content="<?= Route::_(
	RouteHelper::getArticleRoute($displayData['item']->slug, $displayData['item']->catid, $displayData['item']->language)
) ?>">

<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
	<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
		<meta itemprop="url" content="<?= Uri::base(false) ?>images/logos/webp/icon-red@4x.webp">
		<meta itemprop="width" content="400">
		<meta itemprop="height" content="400">
	</div>
	<meta itemprop="name" content="Dionysopoulos.me" />
</div>
