<?php
/**
 * Discussion topic reply form body
 *
 * @uses $vars['entity'] A discussion topic object
 * @uses $vars['inline'] Display a shortened form?
 */


if (isset($vars['entity']) && elgg_is_logged_in()) {
	echo elgg_view('input/hidden', array(
		'name' => 'entity_guid',
		'value' => $vars['entity']->getGUID(),
	));

	$inline = elgg_extract('inline', $vars, false);
	if ($inline) {
		echo elgg_view('input/text', array('name' => 'group_topic_post'));
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
	} else {
?>
	<div>
		<label><?php echo elgg_echo("reply"); ?></label>
		<?php echo elgg_view('input/longtext', array('name' => 'group_topic_post')); ?>
	</div>
	<div class="elgg-foot">
<?php
		echo elgg_view('input/submit', array('value' => elgg_echo('reply')));
?>
	</div>
<?php
	}
}
