<?php
/**
 *  Cassiopeia/TwentyTwo — Dionysopoulos.me Official Site Template
 *
 *  @package     tpl_cassiopeia_twentytwo
 *  @copyright   (C) 2022 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Layout variables
 * -----------------
 *
 * @var   array $displayData   Array with all the given attributes for the image element.
 *                             Eg: src, class, alt, width, height, loading, decoding, style, data-*
 *                             Note: only the alt and src attributes are escaped by default!
 */
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Layout\LayoutHelper;

// Joomla will not register namespaces for templates so we have to do it the hard way
JLoader::registerNamespace('\\Dionysopoulos\\Template\\Site\\TwentyTwo\\', JPATH_THEMES . '/cassiopeia_twentytwo' . '/src');

$img          = HTMLHelper::_('cleanImageURL', $displayData['src']);
$alt          = $displayData['alt'] ?? '';
$caption      = $displayData['caption'] ?? null;
$resize       = (bool) ($displayData['resize'] ?? true);
$webp         = (bool) ($displayData['webp'] ?? true);
$webpPriority = (bool) ($displayData['webp_priority'] ?? true);
$lazy         = (bool) ($displayData['lazy'] ?? true) || ($displayData['loading'] ?? 'lazy') === 'lazy';
$breakpoints  = $displayData['breakpoints'] ?? [];

unset($displayData['src']);
unset($displayData['file']);
unset($displayData['caption']);
unset($displayData['resize']);
unset($displayData['webp']);
unset($displayData['webp_priority']);
unset($displayData['lazy']);
unset($displayData['loading']);
unset($displayData['breakpoints']);

$attributes  = $displayData;
$displayData = [
	'caption'       => $caption,
	'resize'        => $resize,
	'webp'          => $webp,
	'webp_priority' => $webpPriority,
	'lazy'          => false,
	'breakpoints'   => $breakpoints,
];

$displayData['file'] = $this->escape($img->url);

if (!empty($alt) && is_string($alt))
{
	$displayData['alt'] = $this->escape($displayData['alt']);
}

if ($img->attributes['width'] > 0 && $img->attributes['height'] > 0)
{
	$attributes['width']  = $img->attributes['width'];
	$attributes['height'] = $img->attributes['height'];

	if ($lazy)
	{
		$displayData['lazy'] = true;
	}
}

$displayData['attributes'] = $attributes;

echo LayoutHelper::render('dionysopoulos.picture_element', $displayData);