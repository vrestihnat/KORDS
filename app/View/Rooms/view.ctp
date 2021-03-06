<div id="Room" class="span12">
	<h1><?= $room['Location']['name'] . ' - ' . $room['Room']['number']; ?></h1>
	<?= $this->element('breadcrumbs'); ?>

	<!-- Hint about arrow keys -->
	<div id="arrow_hint" class="alert alert-info fade in">
		<a class="close" data-trigger="nix" data-nix-options="'target': '!div.alert'">&times;</a>
		You can use the left and right arrows to scroll between rooms. Try it!
	</div>

	<section id="RoomInfo" class="row">
		<table id="InfoTable" class="table-striped table-condensed span6">
			<tr>
				<td>Location</td>
				<td><?= $room['Location']['name']; ?></td>
			</tr>
			<tr>
				<td>Contract</td>
				<td><?= ($room['Room']['short_contract'] ? 'short only' : 'long or short'); ?></td>
			</tr>
			<tr>
				<td>Rent</td>
				<td><?= $room['RentBand']['humanised']; ?></td>
			</tr>
			<tr>
				<td>Ensuite</td>
				<td class="<?= ($room['Room']['ensuite'] ? 'green' : 'red'); ?>"><?= ($room['Room']['ensuite']) ? 'yes' : 'no'; ?></td>
			</tr>
			<tr>
				<td>Set</td>
				<td class="<?= ($room['Room']['set'] ? 'green' : 'red'); ?>"><?= ($room['Room']['set']) ? 'yes' : 'no'; ?></td>
			</tr>
			<tr>
				<td>Double</td>
				<td class="<?= ($room['Room']['double'] ? 'green' : 'red'); ?>"><?= ($room['Room']['double']) ? 'yes' : 'no'; ?></td>
			</tr>
			<tr>
				<td>Piano</td>
				<td class="<?= ($room['Room']['piano'] ? 'green' : 'red'); ?>"><?= ($room['Room']['piano']) ? 'yes' : 'no'; ?></td>
			</tr>
			<tr>
				<td>Smoking</td>
				<td class="<?= ($room['Room']['smoking'] ? 'green' : 'red'); ?>"><?= ($room['Room']['smoking']) ? 'yes' : 'no'; ?></td>
			</tr>
			<tr>
				<td>Floor</td>
				<td><?= $room['Room']['floor']; ?></td>
			</tr>
			<tr>
				<td>Available to</td>
				<td><?= Inflector::humanize($room['TenantType']['name']); ?></td>
			</tr>
			<tr>
				<td>Ballot Status</td>
				<td class="<?= ($room['Room']['available'] ? 'green' : 'red'); ?>"><?= ($room['Room']['available']) ? 'available' : 'taken'; ?></td>
			</tr>
		</table>

		<div class="centre thumbnails">
			<?php if(count($room['RoomImage']) > 0): ?>
			<ul class="thumbnails">
				<li class="span6">
					<div class="thumbnail">
						<?= $this->Html->image('rooms/'.$room['RoomImage'][0]['image'], array('id'=>'MainImage')); ?>
					</div>
				</li>
			</ul>
			<?php else: ?>
			<span class="label label-warning">No Images</span>
			<?php endif; ?>
		</div>

		<?php if(count($room['RoomImage']) > 1): ?>
		<div class="centre well">
			<ul class="thumbnails" data-behavior="Thumbs" data-thumbs-options="'display':'MainImage'">
				<?php
					foreach($room['RoomImage'] as $photo) {
						echo '<li class="span2">';
						echo $this->Html->link(
								$this->Html->image('rooms/'.$photo['image']),
								'/img/rooms/'.$photo['image'],
								array(
									'escape'				=>	false,
									'class'					=>	'thumbnail'
								));
						echo '</li>';
					}
				?>
			</ul>
		</div>
		<?php endif; ?>
	</section>

	<section id="Comments" class="row hrule">
		<div class="rows">
			<?php foreach($room['Comment'] as $comment): ?>
				<div class="comment row">
					<div class="span8 offset1 comment body">
						<?= Markdown($comment['body']); ?>
						<?php
							if(
								$comment['author'] == $this->Session->read('Raven.user')
								|| $this->Session->read('Kords.user_authorised')
							) {
								echo '<div>';
								echo $this->Form->create('Delete', array(
									'url'		=>	array(
										'controller'	=>	'comments',
										'id'			=>	$comment['id'],
										'action'		=>	'delete'
									),
									'method'	=>	'post',
									'class'		=>	'form-discrete'
								));
								echo $this->Form->hidden('Comment.id', array(
									'value'=>$comment['id']
								));
								echo $this->Form->end(array(
									'label'		=>	'delete',
									'value'		=>	'delete',
									'class'		=>	'btn btn-small btn-danger',
									'div'		=>	false,
									'escape'	=>	false
								));
								echo '</div>';
							}
						?>
					</div>
					<div class="span2 comment info">
						<div class="author">
							<?= $comment['author']; ?>
						</div>
						<div class="date">
							<?= date('jS M Y', strtotime($comment['date'])); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<div id="AddComment" class="centre">
			<?php
				echo $this->Form->create('Comment', array(
					'url'	=>	array('controller'=>'comments', 'action'=>'add')
				));

				echo $this->Form->input('Comment.body', array(
					'div'	=>	'clearfix',
					'type'	=>	'textarea',
					'class'	=>	'span8',
					'label'	=>	false
				));

				echo $this->Form->hidden('Comment.room_id', array('value'=>$room['Room']['id']));

				echo $this->Form->end(array(
					'label'	=>	'Add Comment',
					'value'	=>	'Add Comment',
					'class'	=>	'btn btn-primary',
					'div'	=>	false
				));
			?>

		</div>
	</section>
	
	<?php if($this->Session->check('Kords.user_authorised')): ?>
	<section class="controls centre">
		<?php 
			echo $this->Html->link('Edit', array('id'=>$room['Room']['id'], 'action'=>'edit'), array('class'=>'btn'));
			
			echo '&nbsp;';
			
			echo $this->element('delete_button', array(
				'url'	=>	array(
					'controller'	=>	'rooms',
					'id'			=>	$room['Room']['id'],
					'action'		=>	'delete'
				),
				'model'	=>	'Room',
				'id'	=>	$room['Room']['id']
			));
		?>
	</section>
	<?php endif; ?>
</div>

<script language="javascript" type="text/javascript" defer="defer">
	var Navigator = new RoomNavigator(<?= $room['Room']['id']; ?>);

	$('arrow_hint').getElement('a').addEvent('click', function() {
		Cookie.write('seen_arrow_hint', 1);
	});

	if(Cookie.read('seen_arrow_hint')) {
		$('arrow_hint').hide();
	}
</script>