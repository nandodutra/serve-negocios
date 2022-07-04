<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * OrganicWebs modified - Oct 2021
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu.min.js', [], ['type' => 'module']);
$wa->registerAndUseScript('mod_menu', 'mod_menu/menu-es5.min.js', [], ['nomodule' => true, 'defer' => true]);

$id = '';

if ($tagId = $params->get('tag_id', ''))
{
	$id = ' id="' . $tagId . '"';
}

// The menu class is deprecated. Use mod-menu instead
?>
<ul<?php echo $id; ?> class="navbar-nav <?php echo $class_sfx; ?>">
<?php foreach ($list as $i => &$item){
	// Define the <li> class...
	$itemParams = $item->getParams();
	$class      = 'nav-item item-' . $item->id;

	if ($item->id == $default_id)
	{
		$class .= ' default';
	}

	if ($item->id == $active_id || ($item->type === 'alias' && $itemParams->get('aliasoptions') == $active_id))
	{
		$class .= ' current';
	}

	if (in_array($item->id, $path))
	{
		$class .= ' active';
	}
	elseif ($item->type === 'alias')
	{
		$aliasToId = $itemParams->get('aliasoptions');

		if (count($path) > 0 && $aliasToId == $path[count($path) - 1])
		{
			$class .= ' active';
		}
		elseif (in_array($aliasToId, $path))
		{
			$class .= ' alias-parent-active';
		}
	}

	if ($item->type === 'separator')
	{
		$class .= ' divider';
	}

	if ($item->deeper)
	{
//		$class .= ' deeper '; Old
		$class .= ' dropdown '; // OrganicWebs Bootstrap 5 
	}

	if ($item->parent)
	{
//		$class .= ' parent';	// OLD - not used in Bootstrap 5
	}

	######################
	// Print the <li> tag

	echo '<li class="' . $class . '">';

	switch ($item->type) :
		case 'separator':
		case 'component':
		case 'heading':
		case 'url':  // Formatting the <a> tag
//			require ModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type); // Old Joomla Menu
//			echo "<pre>".print_r ($item,1)."</pre>"; // Debug
			$a_class = $itemParams->get('menu-anchor_css'); 	// OrganicWebs Bootstrap 5		
			if ($item->current) $a_class .= " active"; 			// OrganicWebs Bootstrap 5	
			
			if ($item->parent) {
				// Dropdown Parent menu
				echo "<a class='nav-link dropdown-toggle' href='#' role='button' data-bs-toggle='dropdown' aria-expanded='false'> $item->title</a>"; // OrganicWebs Bootstrap 5
			}
			elseif ($item->level > 1) {
				// Menu Link in a dropdown	
				echo "<a class='dropdown-item $a_class ' href='/$item->route' > $item->title </a>"; // OrganicWebs Bootstrap 5 
			}
			else {
				// Standard Menu Link
				echo "<a class='nav-link $a_class ' href='/$item->route' > $item->title </a>"; // OrganicWebs Bootstrap 5 
			}
			break;

		default:
			require ModuleHelper::getLayoutPath('mod_menu', 'default_url');
			break;
	endswitch;

	// The next item is deeper.
	if ($item->deeper)
	{
//		echo '<ul class="mod-menu__sub list-unstyled small">';  // OLD
		echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';  // OrganicWebs Bootstrap 5
	}
	// The next item is shallower.
	elseif ($item->shallower)
	{
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else
	{
		echo '</li>';
	}
}
?></ul>