<?php
/**
 *  Cassiopeia/TwentyTwo — Dionysopoulos.me Official Site Template
 *
 *  @package     tpl_cassiopeia_twentytwo
 *  @copyright   (C) 2022 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$prefix   = '';
$videoUrl = array_reduce(
	is_array($displayData->jcfields) ? $displayData->jcfields : [],
	fn(?object $carry, ?object $item) => $item?->name === 'video' ? $item : $carry,
	null
)?->rawvalue;
$videoId  = $videoUrl ? \Joomla\CMS\Uri\Uri::getInstance($videoUrl)->getVar('v') : null;

if ($videoId)
{
	$prefix = <<< HTML
<div class="badge bg-dark p-1 px-2">
	<span class="fa fa-film" aria-hidden="true" title="Video content"></span>
	<span class="visually-hidden">Video content:</span>
</div>

HTML;
}

// Create a shortcut for params.
$params  = $displayData->params;
$canEdit = $displayData->params->get('access-edit');

$currentDate = Factory::getDate()->format('Y-m-d H:i:s');
$link = RouteHelper::getArticleRoute($displayData->slug, $displayData->catid, $displayData->language);
?>
<?php if ($displayData->state == 0 || $params->get('show_title') || ($params->get('show_author') && !empty($displayData->author))) : ?>
	<div class="page-header">
		<?php if ($params->get('show_title')) : ?>
			<h2>
				<?= $prefix ?>
				<?php if ($params->get('link_titles') && ($params->get('access-view') || $params->get('show_noauth', '0') == '1')) : ?>
					<a href="<?= Route::_($link); ?>">
						<?= $this->escape($displayData->title); ?>
					</a>
				<?php else : ?>
					<?= $this->escape($displayData->title); ?>
				<?php endif; ?>
			</h2>
		<?php endif; ?>

		<?php if ($displayData->state == 0) : ?>
			<span class="badge bg-warning"><?= Text::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>

		<?php if ($displayData->publish_up > $currentDate) : ?>
			<span class="badge bg-warning"><?= Text::_('JNOTPUBLISHEDYET'); ?></span>
		<?php endif; ?>

		<?php if ($displayData->publish_down !== null && $displayData->publish_down < $currentDate) : ?>
			<span class="badge bg-warning"><?= Text::_('JEXPIRED'); ?></span>
		<?php endif; ?>
	</div>
<?php endif; ?>
