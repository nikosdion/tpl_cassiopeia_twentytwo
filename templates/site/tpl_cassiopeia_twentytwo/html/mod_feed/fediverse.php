<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_feed
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// Check if feed URL has been set
if (empty($rssurl)) {
    echo '<div>' . Text::_('MOD_FEED_ERR_NO_URL') . '</div>';

    return;
}

if (!empty($feed) && is_string($feed)) {
    echo $feed;
} else {
    $lang      = $app->getLanguage();
    $myrtl     = $params->get('rssrtl', 0);
    $direction = ' ';

    $isRtl = $lang->isRtl();

    if ($isRtl && $myrtl == 0) {
        $direction = ' redirect-rtl';
    } elseif ($isRtl && $myrtl == 1) {
        // Feed description
        $direction = ' redirect-ltr';
    } elseif ($isRtl && $myrtl == 2) {
        $direction = ' redirect-rtl';
    } elseif ($myrtl == 0) {
        $direction = ' redirect-ltr';
    } elseif ($myrtl == 1) {
        $direction = ' redirect-ltr';
    } elseif ($myrtl == 2) {
        $direction = ' redirect-rtl';
    }

	$hasTitle       = $feed->title !== null && $params->get('rsstitle', 1) == 1;
	$hasDate        = $params->get('rssdate', 1) == 1;
	$hasDescription = $params->get('rssdesc', 1) == 1;
	$hasImage       = $feed->image && $params->get('rssimage', 1) == 1;
	$profileUrl     = str_replace('@', 'web/@', substr($rssurl, 0, -4));
	$contentWarning = null;

	$fediverseConvertText = function(string $text) use ($params, &$contentWarning): string
	{
		// Make links visible again
		$text = str_replace('<span class="invisible"', '<span ', $text);
		// Strip the images.
		$text = OutputFilter::stripImages($text);

		// Process content warning
		$contentWarning = null;

		if (str_starts_with($text, '<p><strong>Content warning:</strong>'))
		{
			$hrPos = strpos($text, '<hr />');
			$contentWarning = substr($text, 0, $hrPos);
			$contentWarning = str_replace('<strong>Content warning:</strong>', '', $contentWarning);
			$text = substr($text, $hrPos + 6);
		}

		$text = HTMLHelper::_('string.truncate', $text, $params->get('word_count', 0));
		$text = str_replace('&apos;', "'", $text);

		return $text;
	};

    if ($feed !== false) {
        ?>
        <div style="direction: <?php echo $rssrtl ? 'rtl' : 'ltr'; ?>;" class="text-<?php echo $rssrtl ? 'right' : 'left'; ?> feed">

		<?php if ($hasTitle || $hasDate || $hasDescription || $hasImage): ?>
		<div class="mb-3 border-bottom border-muted">
			<?php if ($hasTitle || $hasImage): ?>
			<h4 class="<?= $direction ?>">
				<a href="<?php echo htmlspecialchars($profileUrl, ENT_COMPAT, 'UTF-8'); ?>" target="_blank" rel="noopener" class="d-flex flex-row gap-2 align-items-center justify-content-evenly">
					<?php if ($hasImage): ?>
					<span class="flex-shrink-1">
						<img src="<?= $feed->image->uri ?>" alt="<?= $feed->image->title ?>" class="img-fluid rounded-circle" style="max-width: 2em">
					</span>
					<?php endif ?>
					<?php if ($hasTitle): ?>
					<span class="flex-grow-1">
						<?= $feed->title?>
					</span>
					<?php endif ?>
				</a>
			</h4>
			<?php endif ?>
			<?php if ($hasDescription): ?>
			<p class="small text-muted">
				<?= $feed->description ?>
			</p>
			<?php endif ?>
			<?php if ($hasDate): ?>
			<p class="small text-muted text-center">
				<span class="fa fa-calendar" aria-hidden="true"></span>
				<span class="visually-hidden">Last updated on</span>
				<?= HTMLHelper::_('date', $feed->publishedDate, Text::_('DATE_FORMAT_LC5')) ?>
			</p>
			<?php endif ?>
		</div>

		<?php endif ?>

    <!-- Show items -->
        <?php if (!empty($feed)) { ?>
        <ul class="newsfeed fediverse list-unstyled m-0 p-0">
            <?php for ($i = 0, $max = min(count($feed), $params->get('rssitems', 3)); $i < $max; $i++) { ?>
                <?php
                $uri  = $feed[$i]->uri || !$feed[$i]->isPermaLink ? trim($feed[$i]->uri) : trim($feed[$i]->guid);
                $uri  = !$uri || stripos($uri, 'http') !== 0 ? $rssurl : $uri;
                $text = $feed[$i]->content !== '' ? trim($feed[$i]->content) : '';
                ?>
                <li class="m-0 p-0 <?= ($i !== 0) ? 'border-top border-muted pt-3' : '' ?> pb-1">
                    <?php if ($params->get('rssitemdesc', 1) && $text !== '') : ?>
						<?php
	                    	$text = $fediverseConvertText($text);
						?>
                        <div class="feed-item-description">
							<?php if (!empty($contentWarning)): ?>
								<details class="mb-2">
									<summary>
										<span class="fa fa-exclamation-triangle"
											  title="Content warning: <?= htmlspecialchars(strip_tags($contentWarning), ENT_COMPAT, 'UTF-8') ?>"
											  aria-hidden="true"></span>
										<span class="visually-hidden">Content warning:</span>
										<strong><?= strip_tags($contentWarning) ?></strong>
									</summary>
									<?= $fediverseConvertText($text) ?>
								</details>
							<?php else: ?>
								<?= $fediverseConvertText($text) ?>
							<?php endif; ?>
                        </div>
                    <?php endif; ?>

					<div class="text-end">
						<span class="fa fa-clock" aria-hidden="true"></span>
						<span class="visually-hidden">Tooted on </span>
						<?php if (!empty($uri)) : ?>
						<span class="feed-link">
                        	<a href="<?php echo htmlspecialchars($uri, ENT_COMPAT, 'UTF-8'); ?>" target="_blank" rel="noopener">
                        		<?php echo trim($feed[$i]->title); ?>
							</a>
						</span>
						<?php else : ?>
							<span class="feed-link"><?php echo trim($feed[$i]->title); ?></span>
						<?php endif; ?>
					</div>

                </li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>
    <?php }
}
