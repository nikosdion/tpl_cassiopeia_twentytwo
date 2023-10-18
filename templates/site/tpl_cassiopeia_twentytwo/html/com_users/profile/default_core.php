<?php
/**
 *  Akeeba Ltd – Official Site Template
 *
 *  @package   tpl_akeebabs4
 *  @copyright Copyright (c)2017-2023 Nicholas K. Dionysopoulos / Akeeba Ltd
 *  @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserFactoryInterface;

$myUser     = Factory::getApplication()->getIdentity() ?: new User();
$isThisMe   = $myUser->id == $this->data->id;
$user       = Factory::getContainer()
                     ->get(UserFactoryInterface::class)
                     ->loadUserById($this->data->id);
$avatarURL  = 'https://secure.gravatar.com/avatar/' . md5($user->email) . '.jpg?s=256&d=mm';
$profileURL = 'https://gravatar.com/' . md5($user->email);
?>
<div class="com-users-profile-basic-container row p-4 bg-gradient">
	<p class="h1 mt-0 mb-3 d-block d-lg-none text-center">
		<?= $this->escape($this->data->name) ?>
	</p>
	<div class="com-users-profile-basic-avatar col-12 col-lg-4 text-center">
		<div>
			<img src="<?= $avatarURL ?>" alt="<?= $this->escape($this->data->name) ?>"
				 class="rounded-circle">
		</div>
		<div>
			<a href="<?= $profileURL ?>" class="btn btn-secondary m-3" target="_blank"> Edit avatar on Gravatar <span
						class="icon-external-link-alt"></span> </a>
		</div>
	</div>
	<div class="com-users-profile-basic-info col-12 col-lg-8">
		<p class="h1 mt-0 mb-3 d-none d-lg-block">
			<?= $this->escape($this->data->name) ?>
		</p>
		<dl>
			<dt>
				<?= Text::_('COM_USERS_LOGIN_USERNAME_LABEL'); ?>
			</dt>
			<dd class="font-monospace text-primary fw-bold">
				<?= $this->escape($this->data->username); ?>
			</dd>
			<?php if ($isThisMe): ?>
				<dt>
					<?= Text::_('COM_USERS_PROFILE_EMAIL1_LABEL') ?>
				</dt>
				<dd class="fw-normal">
					<?= $this->escape($this->data->email); ?>
				</dd>
				<dt>
					<?= Text::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL'); ?>
				</dt>
				<dd class="fw-normal">
					<?= HTMLHelper::_('date', $this->data->registerDate, Text::_('DATE_FORMAT_LC1')); ?>
				</dd>
				<dt>
					<?= Text::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL'); ?>
				</dt>
				<dd class="fw-normal">
					<?php if ($this->data->lastvisitDate !== null) : ?>
						<?= HTMLHelper::_('date', $this->data->lastvisitDate, Text::_('DATE_FORMAT_LC1')); ?>
					<?php else: ?>
						<?= Text::_('COM_USERS_PROFILE_NEVER_VISITED'); ?>
					<?php endif; ?>
				</dd>
			<?php endif; ?>
		</dl>

		<?php if ($isThisMe) : ?>
			<div class="edit-profile mt-4 mb-2 d-flex gap-3 align-items-center flex-wrap">
				<a class="btn btn-primary"
				   href="<?= Route::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id); ?>">
					<span class="icon-user" aria-hidden="true"></span>
					<?= Text::_('COM_USERS_EDIT_PROFILE'); ?>
				</a>

				<span class="d-none d-md-flex flex-grow-1"></span>

				<form action="<?php echo Route::_('index.php?option=com_users&task=user.logout'); ?>" method="post">
					<button type='submit' class='btn btn-danger'>
						<span class="fa fa-fw fa-sign-out-alt" aria-hidden="true"></span>
						Log out
					</button>
					<?php echo HTMLHelper::_('form.token'); ?>
				</form>


			</div>
			<div class="mt-4 mb-4 d-flex justify-content-between d-flex align-items-center flex-wrap">

				<a class="btn btn-sm btn-outline-danger"
				   href="<?= Route::_('index.php?option=com_datacompliance&Itemid=1275') ?>">
					<span class="fas fa-user-alt-slash" aria-hidden="true"></span>
					Delete account / Export Data
				</a>
			</div>
		<?php endif; ?>
</div>
</div>