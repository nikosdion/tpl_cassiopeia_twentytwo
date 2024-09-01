<?php
/**
 *  Cassiopeia/TwentyTwo — Dionysopoulos.me Official Site Template
 *
 *  @package     tpl_cassiopeia_twentytwo
 *  @copyright   (C) 2022-2023 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentHelperRoute;

/**
 * @var \Joomla\CMS\Document\HtmlDocument       $doc
 * @var \Joomla\CMS\Application\SiteApplication $app
 */
$doc = $this->document;
$app = Factory::getApplication();

// Set OpenGraph data

$canonicalURL = Route::_(ContentHelperRoute::getArticleRoute($this->item->id), true, Route::TLS_IGNORE, true);

$doc->setMetaData('og:type', 'article', 'property');
$doc->setMetaData('og:title', $this->item->title, 'property');
$doc->setMetaData('og:description', $doc->getDescription(), 'property');
$doc->setMetaData('og:site_name', $app->get('sitename'), 'property');
$doc->setMetaData('og:url', $canonicalURL, 'property');
$doc->setMetaData('twitter:card', 'summary_large_image');
$doc->setMetaData('twitter:site', '@sledge812');
$doc->setMetaData('twitter:creator', '@sledge812');
$doc->setMetaData('twitter:description', $doc->getDescription());
$doc->setMetaData('twitter:title', $this->item->title);

// Add the blogger info card to the beginning of the $this->item->event->afterDisplayContent array

$this->item->event->afterDisplayContent =
	\Joomla\CMS\Layout\LayoutHelper::render('dionysopoulos.blogger_info', [
		'items' => $this->item->item
	])
	. $this->item->event->afterDisplayContent;

// Display video custom field, if necessary
$videoUrl = array_reduce(
	is_array($this->item->jcfields) ? $this->item->jcfields : [],
	fn(?object $carry, ?object $item) => $item?->name === 'video' ? $item : $carry,
	null
)?->rawvalue;
$videoId    = $videoUrl ? \Joomla\CMS\Uri\Uri::getInstance($videoUrl)->getVar('v') : null;

if (!empty($videoUrl)) {
	$videoUri = \Joomla\CMS\Uri\Uri::getInstance($videoUrl);
	$this->item->event->beforeDisplayContent .= <<< HTML
<iframe src="https://www.youtube-nocookie.com/embed/$videoId"
		style="width: 100%; aspect-ratio: 1.78"
		title="YouTube video player" frameborder="0"
		allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
		referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
<div class="my-2 card card-body">
	<p class="m-0">
		<a href="https://www.youtube.com/@NicholasDionysopoulos"
			class="btn btn-primary btn-sm"
		>
			<span class="fab fa-youtube me-1" aria-hidden="true"></span>
			Visit my YouTube channel
		</a>
	</p>
</div>
<p class="mt-1 mb-4 px-3 small text-info">
	<span class="fa fa-info-circle" aria-hidden="true"></span>
	Additional cookies and / or trackers may be used by YouTube when playing back the video.
</p>
HTML;

}

// $this->item->event->beforeDisplayContent .= '';


// Load the core template
include JPATH_COMPONENT . '/tmpl/article/default.php';