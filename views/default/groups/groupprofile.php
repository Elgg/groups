<?php
/**
 * Elgg groups plugin full profile view.
 *
 * @package ElggGroups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 */


$user = get_loggedin_user();
elgg_push_breadcrumb(elgg_echo('groups:all'), "{$vars['url']}pg/groups/world");

// create user actions
$actions = array();

if ($vars['entity']->canEdit()) {
	// breadcrumb trail
	elgg_push_breadcrumb(elgg_echo('groups:yours'), "{$vars['url']}pg/groups/member/{$user->username}");
	
	// edit and invite
	$actions["mod/groups/edit.php?group_guid={$vars['entity']->getGUID()}"] = elgg_echo('groups:edit');
	$actions["mod/groups/invite.php?group_guid={$vars['entity']->getGUID()}"] = elgg_echo('groups:invite');
} 

if ($vars['entity']->isMember($user)) {
	// breadcrumb trail
	elgg_push_breadcrumb(elgg_echo('groups:yours'), "{$vars['url']}pg/groups/member/{$user->username}");
	
	// leave
	$url = elgg_add_action_tokens_to_url("action/groups/leave?group_guid={$vars['entity']->getGUID()}");
	$actions[$url] = elgg_echo('groups:leave');
} else {
	// join
	// admins can always join.
	if ($vars['entity']->isPublicMembership() || $vars['entity']->canEdit()) {
		$url = elgg_add_action_tokens_to_url("action/groups/join?group_guid={$vars['entity']->getGUID()}");
		$actions[$url] = elgg_echo('groups:join');
	} else {
		// request membership
		$url = elgg_add_action_tokens_to_url("action/groups/joinrequest?group_guid={$vars['entity']->getGUID()}");
		$actions[$url] = elgg_echo('groups:joinrequest');
	}
}

/*

*/

// build action buttons
$action_buttons = '';
if (!empty($actions)) {
	$action_buttons = '<div class="content_header_options">';
	foreach ($actions as $url => $action) {
		$action_buttons .= "<a class=\"action_button\" href=\"{$vars['url']}$url\">$action</a>";
	}
	$action_buttons .= '</div>';
}

// display breadcrumb
elgg_push_breadcrumb($vars['entity']->name);
echo elgg_view('navigation/breadcrumbs');

// build and display header
echo <<<__HTML
<div id="content_header" class="clearfloat">
	<div class="content_header_title">
		<h2>{$vars['entity']->name}</h2>
	</div>
	$action_buttons
</div>
__HTML;

?>
<div class="group_profile clearfloat">
	<div class="group_profile_column icon">
		<div class="group_profile_icon">
		<?php
		echo elgg_view(
			"groups/icon", array(
			'entity' => $vars['entity'],
			'size' => 'large',
			));
		?>
		</div>

		<div class="group_stats">
			<?php
				echo "<p><b>" . elgg_echo("groups:owner") . ": </b><a href=\"" . get_user($vars['entity']->owner_guid)->getURL() . "\">" . get_user($vars['entity']->owner_guid)->name . "</a></p>";
			?>
			<p><?php
				$count = $vars['entity']->getMembers(0, 0, TRUE);
				echo elgg_echo('groups:members') . ": " . $count;

			?></p>
		</div>
	</div>

	<div class="group_profile_column info">
		<?php
			if ($vars['full'] == true) {
				if (is_array($vars['config']->group) && sizeof($vars['config']->group) > 0){

					foreach($vars['config']->group as $shortname => $valtype) {
						if ($shortname != "name") {
							$value = $vars['entity']->$shortname;

							if (!empty($value)) {
								//This function controls the alternating class
								$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
							}

							echo "<p class=\"{$even_odd}\">";
							echo "<b>";
							echo elgg_echo("groups:{$shortname}");
							echo ": </b>";

							$options = array(
								'value' => $vars['entity']->$shortname
							);

							if ($valtype == 'tags') {
								$options['tag_names'] = $shortname;
							}

							echo elgg_view("output/{$valtype}", $options);

							echo "</p>";
						}
					}
				}
			}
		?>
	</div>
</div>
