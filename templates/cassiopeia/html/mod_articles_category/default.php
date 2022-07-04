<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   (C) 2010 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

if (!$list)
{
	return;
}

?>
<div class="articles-widget row row-cols-1 row-cols-md-3 g-4">
		<?php $items = $list; ?>
		<?php require ModuleHelper::getLayoutPath('mod_articles_category', $params->get('layout', 'default') . '_items'); ?>
</div>
