<?php
/**
 * Elgg group activity
 */

require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$group_guid = (int)get_input('group');
set_page_owner($group_guid);
if (!(page_owner_entity() instanceof ElggGroup)) {
	forward();
}
group_gatekeeper();

global $CONFIG;

// set up breadcrumbs
$group = get_entity($group_guid);
elgg_push_breadcrumb(elgg_echo('groups'), $CONFIG->wwwroot."pg/groups/world/");
elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('groups:activity'));

$area1 = elgg_view('navigation/breadcrumbs');

$limit = get_input("limit", 20);
$offset = get_input("offset", 0);
$group_guid = get_input("group", 7);
// Sanitise variables -- future proof in case they get sourced elsewhere
$limit = (int) $limit;
$offset = (int) $offset;
$group_guid = (int) $group_guid;

$entities = elgg_get_entities(array(
	'container_guids' => $group_guid,
	'group_by' => 'e.guid'
));

$entity_guids = array();
foreach ($entities as $entity) {
	$entity_guids[] = $entity->getGUID();
}

if (count($entity_guids) > 0) {
	$river_items = elgg_view_river_items('', $entity_guids, '', '', '', '', $limit);
} else {
	$river_items .= elgg_echo('groups:no_activity');
}

$area1 .= elgg_view_title(elgg_echo('groups:activity'));
$area1 .= "<div class='group_listings hide_comments'>".$river_items."</div>";
$title = sprintf(elgg_echo("groups:activity"), page_owner_entity()->name);
$body = elgg_view_layout('one_column_with_sidebar', $area1);

// Finally draw the page
page_draw($title, $body);