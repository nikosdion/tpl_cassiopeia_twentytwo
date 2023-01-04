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
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

/**
 * Blogger biographical information are only available for non-pseudonymous contributors.
 */
if (!empty($displayData['item']->created_by_alias))
{
	return;
}

// Get basic information about the author
$user      = Factory::getUser($displayData['item']->created_by);
$author    = ($displayData['item']->created_by_alias ?: $displayData['item']->author) ?: $user->name;
$authorURL = $displayData['item']->contact_link ?? null;
/**
 * Note: We will put `‘s` after the first name. This is correct even if the name ends in `s`.
 * @see https://prowritingaid.com/apostrophe-after-s
 */
[$firstName,] = explode(' ', $author);

// Get the user custom fields keyed by field name
$fieldValues = FieldsHelper::getFields('com_users.user', $user, true);
$fieldKeys   = array_map(fn(object $field) => $field->name, $fieldValues);
$fields      = array_combine($fieldKeys, $fieldValues);
unset($fieldKeys, $fieldValues);

// Get the bio information from the user's profile
$bio = isset($fields['bio']) ? $fields['bio']->value : '';

if (empty($bio))
{
	// I actually need this to display bio info, right?!
	return;
}

// Stuff I need to talk to the Akeeba Engage plugins
$app            = Factory::getApplication();
$filterFunction = fn($carry, $result) => $carry ?? ((empty($result) || !is_string($result)) ? $carry : $result);

PluginHelper::importPlugin('engage');

// Get the avatar from the custom field OR Akeeba Engage plugins, whichever returns something
$avatarHTML = '';

if (isset($fields['profile-photo']) && !empty(@json_decode($fields['profile-photo']->rawvalue)))
{
	$avatarHTML = $fields['profile-photo']->value;
}

if (empty($avatarHTML))
{
	$avatarResults = $app->triggerEvent('onAkeebaEngageUserAvatarURL', [$user, 128]);
	$avatarURL     = array_reduce($avatarResults, $filterFunction, null);
	$avatarHTML    = HTMLHelper::_('image', $avatarURL, sprintf('Photo of %s', $author));
}

// Get the profile URL from the Akeeba Engage plugins
if (empty($authorURL))
{
	$profileResults = $app->triggerEvent('onAkeebaEngageUserProfileURL', [$user]);
	$authorURL      = array_reduce($profileResults, $filterFunction, null);
}

?>
<div class="card blogger-bio">
	<h3 class="card-header blogger-bio-name">
		<?= $this->escape($author) ?>
	</h3>
	<?php if ($avatarHTML) : ?>
	<div class="d-md-none img-fluid blogger-bio-mobile-image">
		<?= $avatarHTML ?>
	</div>
	<div class="card-body">
		<div class="d-flex flex-row gap-2">
			<div class="d-none d-md-flex blogger-bio-desktop-image"><?= $avatarHTML ?></div>
			<div class="blogger-bio-details">
				<div class="blogger-bio-blurb">
					<?= $bio ?>
				</div>
				<div class="blogger-bio-contact">
					<a class="btn btn-sm btn-outline-primary"
					   href="<?= $authorURL ?>"
					   rel="nofollow"
					   target="_blank"
					>
						<span class="fa fa-external-link" aria-hidden="true"></span>
						<?= $firstName ?>‘s profile
						<span class="visually-hidden">(External link)</span>
					</a>
				</div>
			</div>
		</div>
	</div>
	<?php endif ?>
</div>
