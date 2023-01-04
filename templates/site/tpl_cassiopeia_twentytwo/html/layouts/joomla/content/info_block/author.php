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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

// Get the author's avatar and profile link, if any
$avatarURL      = null;
$profileResults = null;
$author         = ($displayData['item']->created_by_alias ?: $displayData['item']->author);

// Avatars and profiles only apply on articles which have not been filed pseudonymously for privacy reasons
if (empty($displayData['item']->created_by_alias))
{
	PluginHelper::importPlugin('engage');
	$user = Factory::getUser($displayData['item']->created_by);
	$app  = Factory::getApplication();

	// Get the user custom fields keyed by field name
	$fieldValues = FieldsHelper::getFields('com_users.user', $user, true);
	$fieldKeys   = array_map(fn(object $field) => $field->name, $fieldValues);
	$fields      = array_combine($fieldKeys, $fieldValues);
	unset($fieldKeys, $fieldValues);

	$filterFunction = fn($carry, $result) => $carry ?? ((empty($result) || !is_string($result)) ? $carry : $result);

	if (isset($fields['profile-photo']) && !empty(@json_decode($fields['profile-photo']->rawvalue)))
	{
		$avatarURL = $fields['profile-photo']->rawvalue;
		$info      = json_decode($fields['profile-photo']->rawvalue);
		$avatarURL = HTMLHelper::_('cleanImageURL', $info->imagefile)->url;
	}
	else
	{
		$avatarResults = $app->triggerEvent('onAkeebaEngageUserAvatarURL', [$user, 96]);
		$avatarURL     = array_reduce($avatarResults, $filterFunction, null);
	}

	$profileResults = $app->triggerEvent('onAkeebaEngageUserProfileURL', [$user]);
	$profileURL     = array_reduce($profileResults, $filterFunction, null);
}

$authorURL = $displayData['item']->contact_link ?? null;
$target    = '_self';
$rel       = 'author';

if (empty($authorURL) && !empty($avatarURL) && !empty($profileURL))
{
	$displayData['params']->set('link_author', 1);
	$authorURL = $profileURL;
	$target    = '_blank';
	$rel       = 'noopener';
}

?>
<dd class="createdby" itemprop="author" itemscope itemtype="https://schema.org/Person">
	<?php if ($avatarURL): ?>
		<img src="<?= $avatarURL ?>" width="32" height="32" itemprop="image" alt="" class="rounded-circle author-image">
	<?php endif; ?>

	<?php $author = '<span itemprop="name">' . $author . '</span>'; ?>
	<?php if (!empty($authorURL) && $displayData['params']->get('link_author') == true) : ?>
		<?php echo Text::sprintf('COM_CONTENT_WRITTEN_BY', HTMLHelper::_('link', $authorURL, $author, ['itemprop' => 'url'])); ?>
	<?php else : ?>
		<?php echo Text::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
	<?php endif; ?>
</dd>
