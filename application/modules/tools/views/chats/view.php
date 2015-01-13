<div class="chat-view" id="chat-room-<?=$chat->id?>">
	<div class="panel panel-default">
		<div class="panel-heading">
			<b><i class="fa fa-comments-o"></i> Messages</b>
		</div>

		<div class="panel-body">
			<div class="panel panel-default">
				<div class="panel-body">
					<h4>
					<button class="pull-right btn-success btn btn-sm btn-create-message" data-id="<?=$chat->id?>"><i class="fa fa-mail-forward"></i> Send</button>
					<i class="fa fa-<?=$is_admin?'home':'user'?> fa-border"></i>
					New message
					<span class="text-muted"><small>(<span id="chat-<?=$chat->id?>-count-char">300</span> characters left)</small></span>
					</h4>
					<p class="markdown-help label label-info pull-right"><i class="fa fa-info-circle"></i> Markdown</p>
					<textarea id="input-message-content-<?=$chat->id?>" data-id="<?=$chat->id?>" maxlength=300 rows=2 class="textarea-full form-control"></textarea>
				</div>
			</div>

			<?php if ($chat->messages->result_count() == 0):?>
			<div class="panel panel-default">
				<div class="panel-body">
					<p class="text-muted">No messages</p>
				</div>
			</div>
			<?php endif ?>

			<?php $count=0; foreach ($chat->messages as $message): ?>

			<?php $count ++; if ($count == 5):?>
				<button class="btn btn-default btn-xs" data-toggle="collapse" data-target=".expand-chat-<?=$chat->id?>">
				<i class="fa fa-expand"></i> Show all messages (<?=$chat->message->result_count()-4?> more)
				</button>

				<div class="collapse expand-chat-<?=$chat->id?>"><br> 
			<?php endif ?>

			<div class="panel panel-default">
				<div class="panel-body">
					<?php if ($is_admin OR ($message->contact_id == $account_id)): ?>
					<button title="Delete" data-message_id="<?=$message->id?>" data-chat_id="<?=$chat->id?>" class="btn-delete-message btn btn-danger btn-sm pull-right"><i class="fa fa-trash-o"></i></button>
					<?php endif ?>
				
					<i class="fa fa-<?=is_null($message->contact_id)?'home':'user'?> fa-lg fa-border pull-left"></i>
					<p class="text-muted"><?=$message->contact->name?> <?=format_date("F j, g:i a",$message->date)?></p>
					<br><p class="markdown-content"><?=nl2br($message->content)?></p>
				</div>
			</div>
			<?php endforeach ?>
			<?php if ($chat->messages->result_count() > 10) echo '</div>'; ?>
		</div>
	</div>

<script defer>
$(function() {
	$(".btn-create-message").click(create_message);
	$(".btn-delete-message").click(delete_message);
	$("#input-message-content-<?=$chat->id?>").keyup(update_count_length);
	$("#input-message-content-<?=$chat->id?>").change(update_count_length);
		
	function update_count_length ()
	{
		$("#chat-"+$(this).data("id")+"-count-char").html(
			$(this).attr("maxlength")-$(this).val().length);
	}

	function delete_message ()
	{
		var chat_id = $(this).data("chat_id");
		var message_id = $(this).data("message_id");

		var text = $(this).data("text");
		var url = ARNY.site_url+"tools/chats/delete_message/"+message_id;
		var token = Math.floor((Math.random()*100000)+1);

		var html = 'Do you really want to perform this action ?'+
			'<button id="delete-message-'+token+'"'+
			'class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Confirm</button>';

		var notify = new PNotify({
			title: 'Delete message',
			text: html,
			closer: true,
			sticker: false,
		});

		$("#delete-message-"+token).click( function () {
			$.ajax({url : url}).success(function(data){
				$("#chat-room-"+chat_id).html(data);
			});
			notify.remove();
		});
	}

	function create_message ()
	{
		var id = $(this).data("id");
		var content = $("#input-message-content-"+id).val();

		var url = ARNY.site_url+"tools/chats/create_message/"+id;
		var obj = {	content : content};

		$.post(url,obj).done(function(data){
			$("#chat-room-"+id).html(data);
			bind_all_markdown("#chat-room-"+id);
		});
	}
});
</script>
</div>
