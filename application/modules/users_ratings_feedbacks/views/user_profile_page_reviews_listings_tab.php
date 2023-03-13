<div class="reviews">
	<!-- Radio Button Start -->									
	<div class="default_radio_square_button mainRadio_btn">
		<section>
			<div>
				<input type="radio" id="dispRadio1" name="dispRadio1Options" value="1"  data-tab-type="projects" class="users_ratings_feedback_listing_tab">
				<label for="dispRadio1">
					<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_projects_tab');?></span>
				</label>
			</div>
			<div>
				<input type="radio" id="dispRadio2" name="dispRadio1Options" value="2"  data-tab-type="fulltime_projects" class="users_ratings_feedback_listing_tab">
				<label for="dispRadio2">
					<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_tab');?></span>
				</label>
			</div>
		</section>
	</div>
	<!-- Radio Button End -->
	<div>
		<div id="on_projects_list" class="reviewUsers">
			<!-- Radio Button Start -->									
			<div class="default_radio_square_button subRadio_btn">
				<section>
					<div>
						<input type="radio" id="dispRadio3" name="dispRadio" value="3"  data-tab-type="sp" class="users_ratings_feedback_listing_tab">
						<label for="dispRadio3">
							<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_sp_tab');?></span>
						</label>
					</div>
					<div>
						<input type="radio" id="dispRadio4" name="dispRadio" value="4"  data-tab-type="po" class="users_ratings_feedback_listing_tab">
						<label for="dispRadio4">
							<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_projects_as_po_tab');?></span>
						</label>
					</div>
				</section>
			</div>
			<!-- Radio Button End -->
			<div>
				<div class="reviewProject_details" id="as_service_provider_list">
					<div class="scAvg"><span id="as_service_provider_list_heading"></span></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12" id="as_service_provider_container"></div>
					</div>
				</div>
				<div class="reviewProject_details" id="as_project_owner_list" style="display: none;">
					<div class="scAvg"><span id="as_project_owner_list_heading"></span></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12" id="as_project_owner_container"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="reviewUsers" id="on_fulltime_jobs_list" style="display: none;">
			<!-- Radio Button Start -->									
			<div class="default_radio_square_button subRadio_btn">
				<section>
					<div>
						<input type="radio" id="dispRadio5" name="dispMyRadio" value="5"  data-tab-type="employee" class="users_ratings_feedback_listing_tab">
						<label for="dispRadio5">
							<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employee_tab');?></span>
						</label>
					</div>
					<div>
						<input type="radio" id="dispRadio6" name="dispMyRadio" value="6"  data-tab-type="employer" class="users_ratings_feedback_listing_tab">
						<label for="dispRadio6">
							<span><?php echo $this->config->item('user_profile_page_ratings_feedbacks_on_fulltime_projects_as_employer_tab');?></span>
						</label>
					</div>
				</section>
			</div>
			<!-- Radio Button End -->
			<div>
				<div class="reviewProject_details" id="as_employee_list">
					<div class="scAvg"><span id="as_employee_list_heading"></span></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12" id="as_employee_container"></div>
					</div>	
				</div>
				<div class="reviewProject_details" id="as_employer_list" style="display: none;">
					<div class="scAvg"><span id="as_employer_list_heading"></span></div>
					<div class="row">
						<div class="col-md-12 col-sm-12 col-12" id="as_employer_container"></div>
					</div>	
				</div>
				</div>
			</div>
		</div>
	</div>
</div>