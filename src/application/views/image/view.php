<?php defined('BASEPATH') or exit ('No direct script access allowed');
if (isset($variety)):

	if (!get_value($variety, 'image_id', FALSE) && IS_EDITOR):

		echo create_button([
				'text' => 'Add Image',
				'class' => [
					'new',
					'button',
					'small',
					'add-image',
				],
				'id' => 'add-image_$id',
				'data_values' => [
					'variety_id' => $variety->id,
				],
				'href' => base_url('variety/new_image/' . $variety->id),
			]
		);
		?>
	<?php else: ?>
		<div class="center">
			<img
				src="https://nyc3.digitaloceanspaces.com/t7-live-fsmn/db.friendsschoolplantsale.com/files/<?php print $variety->id; ?>.jpg"
				class="photo" alt="image of <?php print $variety->common_name; ?> "/>
			<?php if (IS_EDITOR): ?>
				<?php echo create_button([
					'text' => 'Delete Image',
					'class' => ['button', 'delete', 'delete-image'],
					'id' => 'delete-image',
					'data_values' => ['id' => $variety->image_id],
				]); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
<?php endif;
