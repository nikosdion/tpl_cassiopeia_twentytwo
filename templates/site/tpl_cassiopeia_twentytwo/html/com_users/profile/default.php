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
<div class="com-users-profile profile container">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1>
				<?= $this->escape($this->params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>

	<?= $this->loadTemplate('core'); ?>

</div>
