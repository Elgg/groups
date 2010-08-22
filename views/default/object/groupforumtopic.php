<?php
/**
 * Elgg Groups latest discussion listing
 *
 * @package ElggGroups
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
*/

//get the required variables
$title = htmlentities($vars['entity']->title, ENT_QUOTES, 'UTF-8');
//$description = get_entity($vars['entity']->description);
$topic_owner = get_user($vars['entity']->owner_guid);
$group = get_entity($vars['entity']->container_guid);
$forum_created = elgg_view_friendly_time($vars['entity']->time_created);
$counter = $vars['entity']->countAnnotations("generic_comment");
$last_post = $vars['entity']->getAnnotations("generic_comment", 1, 0, "desc");
//get the time and user
if ($last_post) {
	foreach($last_post as $last) {
		$last_time = $last->time_created;
		$last_user = $last->owner_guid;
	}
}

$u = get_user($last_user);

//select the correct output depending on where you are
if (get_context() == "search") {
	var_export($counter);
	if($counter == 1){
    	$info = "<p class='entity_subtext groups'>" . sprintf(elgg_echo('groups:forum:created:single'), $forum_created, $counter) .  "<br />";
	}else{
		$info = "<p class='entity_subtext groups'>" . sprintf(elgg_echo('groups:forum:created'), $forum_created, $counter) .  "<br />";
	}
    if (($last_time) && ($u)) $info.= sprintf(elgg_echo('groups:lastupdated'), elgg_view_friendly_time($last_time), " <a href=\"" . $u->getURL() . "\">" . $u->name . "</a>");
    $info .= '</p>';
	//get the group avatar
	$icon = elgg_view("profile/icon",array('entity' => $u, 'size' => 'tiny'));
    //get the group and topic title
    $info .= "<p class='entity_subtext'><b>" . elgg_echo('Topic') . ":</b> <a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$vars['entity']->guid}&group_guid={$group->guid}\">{$title}</a></p>";
    if ($group instanceof ElggGroup) {
    	$info .= "<p class='entity_title'><b>" . elgg_echo('group') . ":</b> <a href=\"{$group->getURL()}\">".htmlentities($group->name, ENT_QUOTES, 'UTF-8') ."</a></p>";
    }

} else {
	if($counter == 1){
		$info = "<p class='entity_subtext groups'>" . sprintf(elgg_echo('groups:forum:created:single'), $forum_created, $counter) . "</p>";
	}else{
		$info = "<p class='entity_subtext groups'>" . sprintf(elgg_echo('groups:forum:created'), $forum_created, $counter) . "</p>";
	}
    $info .= "<p class='entity_title'>" . elgg_echo('groups:started') . " " . $topic_owner->name . ": <a href=\"{$vars['url']}mod/groups/topicposts.php?topic={$vars['entity']->guid}&group_guid={$group->guid}\">{$title}</a></p>";

    if (groups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {
        	// display the delete link to those allowed to delete
          	$info .= "<div class='entity_metadata'>";
			$info .= '<span class="entity_edit">' . elgg_view("output/url", array(
               																'href' => $vars['url'] . "mod/groups/edittopic.php?group={$vars['entity']->container_guid}&topic={$vars['entity']->guid}",
               																'text' => elgg_echo('edit'),
               															));
			$info .= '</span>';
			
        	// display the delete link to those allowed to delete
          	$info .= '<span class="delete_button">' . elgg_view("output/confirmlink", array(
               																'href' => $vars['url'] . "action/groups/deletetopic?topic=" . $vars['entity']->guid . "&group=" . $vars['entity']->container_guid,
               																'text' => elgg_echo('delete'),
               																'confirm' => elgg_echo('deleteconfirm'),
               															));
			$info .= "</span></div>";

   }

	if (($last_time) && ($u)) {
		$info.= "<p class='entity_subtext'>" . elgg_echo('groups:updated') . " " . elgg_view_friendly_time($last_time) . " by <a href=\"" . $u->getURL() . "\">" . $u->name . "</a></p>";
	}
    //get the user avatar
	$icon = elgg_view("profile/icon",array('entity' => $topic_owner, 'size' => 'tiny'));
}

//display
echo elgg_view_listing($icon, $info);