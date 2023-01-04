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

// Load the core template
include JPATH_COMPONENT . '/tmpl/article/default.php';