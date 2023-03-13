<button class="btn default_btn green_btn feedback_reply" id="<?php echo "feedback_reply_".$section_id ?>" data-id="<?php echo $section_id; ?>"><?php echo $this->config->item('reply_btn_txt') ?><i class="fas fa-reply" aria-hidden="true"></i></button>
<div id="<?php echo "feedback_reply_section_".$section_id ?>" class="hide replySection" style="display:none">
	<form id="<?php echo "feedback_reply_form_".$section_id; ?>" name="feedback_reply_form">
		<textarea  data-id="<?php echo $section_id; ?>"  id="<?php echo "reply_textarea_".$section_id; ?>" class="default_textarea_field reply_text_area avoid_space_textarea"  name="rating_feedback_reply" rows="3" maxlength="<?php echo $this->config->item('users_ratings_feedbacks_reply_characters_maximum_length_characters_limit')?>"></textarea>
		<input type="hidden" value="<?php echo $section_id; ?>" name="section_id" />
		<input type="hidden" value="<?php echo $view_type; ?>" name="view_type" />
		<input type="hidden" value="<?php echo $project_id ?>" name="project_id" />
		<input type="hidden" value="<?php echo  Cryptor::doEncrypt($feedback_recived_by) ?>" name="feedback_recived_by" />
		<input type="hidden" value="<?php echo  Cryptor::doEncrypt($feedback_given_by) ?>" name="feedback_given_by" />
		<div class="error_div_sectn default_error_div_sectn clearfix">
			<span class="content-count feedback_description_length_count_message"><?php echo $this->config->item('users_ratings_feedbacks_reply_characters_maximum_length_characters_limit')."&nbsp;".$this->config->item('characters_remaining_message'); ?></span>
			<span class="userFeedbackOnly">
				<span id="<?php echo "feedback_reply_".$section_id."_error"; ?>" class="error_msg"></span>
				<span class="amBtn">
					<button type="button" class="btn default_btn red_btn cancel_feedback_reply" data-id="<?php echo $section_id; ?>"><?php echo $this->config->item('cancel_btn_txt') ?></button>
					<button class="btn default_btn blue_btn save_feedback_reply" data-id="<?php echo $section_id; ?>"><?php echo $this->config->item('save_btn_txt') ?></button>
				</span>
			</span>
		</div>
	</form>
	<!-- <div class="amBtn">
		<button class="btn default_btn blue_btn save_feedback_reply" data-id="<?php echo $section_id; ?>"><?php echo $this->config->item('save_btn_txt') ?></button>
		<button type="button" class="btn default_btn red_btn cancel_feedback_reply" data-id="<?php echo $section_id; ?>"><?php echo $this->config->item('cancel_btn_txt') ?></button>
	</div> -->
</div>