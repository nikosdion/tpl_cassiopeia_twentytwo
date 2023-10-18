<?php
/**
 *  Akeeba Ltd – Official Site Template
 *
 *  @package   tpl_akeebabs4
 *  @copyright Copyright (c)2017-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 *  @license   GNU General Public License version 3, or later
 */

/**
 * @package         Joomla.Site
 * @subpackage      com_users
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license         GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

/**
 * @var string    $group
 * @var FormField $field
 * @var array     $customFields
 */

?>
<?php if (array_key_exists($field->fieldname, $customFields)) : ?>
	<?= strlen($customFields[$field->fieldname]->value) ? $customFields[$field->fieldname]->value : Text::_('COM_USERS_PROFILE_VALUE_NOT_FOUND'); ?>
<?php elseif (HTMLHelper::isRegistered('users.' . $field->id)) : ?>
	<?= HTMLHelper::_('users.' . $field->id, $field->value); ?>
<?php elseif (HTMLHelper::isRegistered('users.' . $field->fieldname)) : ?>
	<?= HTMLHelper::_('users.' . $field->fieldname, $field->value); ?>
<?php elseif (HTMLHelper::isRegistered('users.' . $field->type)) : ?>
	<?= HTMLHelper::_('users.' . $field->type, $field->value); ?>
<?php else :
	$value = is_string($field->value) ? trim($field->value) : $field->value;
	?>
	<?php if (empty($value)): ?>
	<?= Text::_('COM_USERS_PROFILE_VALUE_NOT_FOUND') ?>
	<?php elseif ($group === 'ats'): ?>
	<?= $value ?>
	<?php else: ?>
	<?= htmlentities($value) ?>
	<?php endif; ?>
<?php endif; ?>
