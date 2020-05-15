	<?=form_open($base_url, 'class="tbl-ctrls"')?>
		<fieldset class="tbl-search right">
			<a class="btn tn action" href="<?=ee('CP/URL')->make('addons/settings/metaweblog_api/create')?>"><?=lang('create_new')?></a>
		</fieldset>
		<h1><?=lang('metaweblog_settings')?></h1>

		<?=ee('CP/Alert')->get('metaweblog-form')?>

		<?php $this->embed('ee:_shared/table', $table); ?>
		<?=$pagination?>
		<?php $this->embed('ee:_shared/form/bulk-action-bar', [
			'options' => [
				[
					'value' => "",
					'text' => '-- ' . lang('with_selected') . ' --'
				],
				[
					'value' => "remove",
					'text' => lang('delete'),
					'attrs' => ' data-confirm-trigger="selected" rel="modal-confirm-remove"'
				]
			],
			'modal' => true
		]); ?>
	<?=form_close();?>

<?php
$modal_vars = array(
	'name'      => 'modal-confirm-remove',
	'form_url'	=> ee('CP/URL')->make('addons/settings/metaweblog_api/remove'),
	'hidden'	=> array(
		'bulk_action'	=> 'remove'
	)
);

$modal = $this->make('ee:_shared/modal_confirm_remove')->render($modal_vars);
ee('CP/Modal')->addModal('remove', $modal);
?>
