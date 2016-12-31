<?php while($content = $list->hasNextReply()):?>
<tr>
	<td class="kboard-list-uid"></td>
	<td class="kboard-list-title" style="padding-left: <?php echo intval($depth*10)?>px"><div class="cut_strings">
			<a href="<?php echo $url->set('uid', $content->uid)->set('mod', 'document')->toString()?>"><img src="<?php echo $skin_path?>/images/icon_reply.png"> <?php if($board->isAdmin() || ($content->member_uid == get_current_user_id())):?><?php echo $content->title?><?php elseif($content->member_uid == 1):?>문의에 대한 답변입니다.<?php else:?>답변입니다.<?php endif?></a>
			<?php echo $content->getCommentsCount()?>
		</div></td>
	<td class="kboard-list-user"><?php echo $content->member_display?></td>
	<td class="kboard-list-date"><?php echo date("Y.m.d", strtotime($content->date))?></td>
	<td class="kboard-list-view"><?php echo $content->view?></td>
</tr>
<?php $boardBuilder->builderReply($content->uid, $depth+1)?>
<?php endwhile?>
