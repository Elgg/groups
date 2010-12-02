<?php

	$performed_by = get_entity($vars['item']->subject_guid);
	$object = get_entity($vars['item']->object_guid);
	$objecturl = $object->getURL();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("groups:river:member", array($url)) . " ";
	$string .= " <a href=\"" . $object->getURL() . "\">" . $object->name . "</a>";
	$string .= " <span class='entity-subtext'>". elgg_view_friendly_time($vars['item']->posted);
	$string .= "</span>";
?>

<?php echo $string; ?>