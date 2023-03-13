<div class="dashTop">
	<div class="membership_content">
		<div class="membership_header">
			<h3 class="top_header_txt">Členství</h3>
			<?php
			if($check_user_upgrade_gold_membership == 0){
			?>
			<div class="top_header_desc_txt">
			<p>Momentálně odebíráte základní členství. Získejte plné výhody Travai funkcí a přejděte na Perfektní členství.</p><p>Je ZDARMA a navíc vám udělíme dalších <span class="display-inline-block">10 000 Kč</span> na BONUS konto.</p>
			</div>
			<?php
			}
			?>
		</div>
		<!-- Mebership 1st Step Start -->
		<div class="membership_body">
			<div class="memTopPart">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-12 membship_topLeft">
						<h5>Srovnání všech funkcí</h5>
					</div>
					<div class="col-md-3 col-sm-3 col-12 membship_topMiddle">
						<div class="topLeftTab <?php echo ($current_plan['current_membership_plan_id'] != 4) ? "topLRnobtn1":""; ?>">
							<h6 class="default_black_bold">Základní</h6>
							<p>Vždy zdarma</p>
							<div class="topLeftTabBtn"><?php if ($current_plan['current_membership_plan_id'] == 4){?>
							<button class="btn default_btn membership_red_btn btn-upgrade-downgrade" id="membership_1" data-id="1" plan="Free"><?php echo $this->config->item('downgrade_membership_btn_text') ?></button>
							<?php }else{ ?>
							<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>
							<?php } ?></div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-12 membship_topRight">
						<div class="topRightTab <?php echo ($current_plan['current_membership_plan_id'] != 1) ? "topLRnobtn1":""; ?>">
							<h6 class="default_black_bold">Perfektní</h6>
							<p><span>0</span>Kč/měs</p>
							<div class="topRightTabBtn">
							<?php if ($current_plan['current_membership_plan_id'] == 1){?>
							<button class="btn default_btn blue_btn btn-upgrade-downgrade"  id="membership_4" data-id="4" plan="Gold"><?php echo $this->config->item('upgrade_membership_btn_text') ?></button>
							<?php }else{ ?>
							<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>
							<?php } ?>
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" id="tabPosition" value="">
			</div>
			<div class="membership_subPart">
				<!-- Inzeráty Start -->
				<div class="firstPart">
					<!-- Subpart Top Head Start -->
					<div class="row subpart_main_top">
						<div class="col-md-12 col-sm-12 col-12 typeHead">
							<div class="default_black_bold_medium">Inzeráty</div>
						</div>
					</div>
					<!-- Subpart Top Head End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Zveřejňování inzerátů</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">zdarma</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">zdarma</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet inzerátů ke zveřejnění za měsíc</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet inzerátů na které lze posílat nabídky a žádosti</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet volných slotů pro návrhy inzerátů projektu</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">25</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet volných slotů pro zveřejněné inzeráty projektu</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">100</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- added on 14.09.2020 -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet volných slotů pro návrhy inzerátů pracovní pozice</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">25</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet volných slotů pro zveřejněné inzeráty pracovní pozice</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">100</h6>
						</div>
					</div>
					<!-- Subpart Body End -->



					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet přijatých nabídek na všechny typy inzeratů</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">neomezené</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Neomezený přístup k funkci <span>Travai Bezpečná Platba</span></h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">ano</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">ano</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
				</div>
				<!-- Inzeráty End -->
				
				<!-- Vylepšení inzerátů zahrnuté v předplatném Start -->
				<div class="secondPart">
					<!-- Subpart Top Head Start -->
					<div class="row subpart_top">
						<div class="col-md-12 col-sm-12 col-12 typeHead">
							<div class="default_black_bold_medium">Vylepšení inzerátů zahrnuté v předplatném</div>
						</div>
					</div>
					<!-- Subpart Top Head End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet vylepšení inzerátu <span>ZVÝRAZNĚNÝ</span></h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">2</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">5</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet vylepšení inzerátu <span>URGENTNÍ</span></h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">2</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">5</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet vylepšení inzerátu <span>NEVEŘEJNÝ</span></h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">2</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">5</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet vylepšení inzerátu <span>SKRYTÝ</span></h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">2</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">5</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
				</div>
				<!-- Vylepšení inzerátů zahrnuté v předplatném End -->
				
				<!-- Profil Start -->
				<div class="thirdPart">
					<!-- Subpart Top Head Start -->
					<div class="row subpart_top">
						<div class="col-md-12 col-sm-12 col-12 typeHead">
							<div class="default_black_bold_medium">Profil</div>
						</div>
					</div>
					<!-- Subpart Top Head End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">TOP umístění v žebříčku seznamu odborníků</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">ne</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">ano</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Uvedení odborných činností</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">3 kategorie a 3 podkategorie v každé kategorii</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">15 kategorií a 3 podkategorie v každé kategorii</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet dovedností</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">50</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet poskytovaný služeb</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">50</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet cizích jazyků</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">3</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">25</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Vlastní grafika profilu</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">ne</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">ano</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet portfolií</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">25</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Počet obrázků v jednom portfoliu</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">15</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Vlastní grafika v portfoliu</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">ano</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">ano</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
				</div>
				<!-- Profil End -->
				
				<!-- Další Start -->
				<div class="fourthPart">
					<!-- Subpart Top Head Start -->
					<div class="row subpart_top">
						<div class="col-md-12 col-sm-12 col-12 typeHead">
							<div class="default_black_bold_medium">Další</div>
						</div>
					</div>
					<!-- Subpart Top Head End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Oblíbení zaměstnavatelé a partneři</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">5</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">neomezeně</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Oznámení nových izerátů v reálném čase</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">ano</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">ano</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Příjmy z doporučení</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">pouze z 1. úrovně (2.5%)</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">1. úroveň (5%) a 2. úroveň (1.25%)</h6>
						</div>
					</div>
					<!-- Subpart Body End -->

					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Odeslání pozvání na inzerát</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">10 pozvání (maximálně 3 na každý inzerát)</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">250 pozvání (maximálně 25 na každý inzerát)</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
					<!-- Subpart Body Start -->
					<div class="row subpart_top">
						<div class="col-md-6 col-sm-6 col-12 typeOne">
							<div class="typeText">
								<h6 class="default_black_regular">Odesílat a přijímat žádosti o spojení</h6>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeTwo">
							<h6 class="membership_regular_text">neomezeně</h6>
						</div>
						<div class="col-md-3 col-sm-3 col-12 typeThree">
							<h6 class="membership_regular_text">neomezeně</h6>
						</div>
					</div>
					<!-- Subpart Body End -->
				</div>
				<!-- Další End -->
			</div>
			<!-- Subpart Body Start -->
			<div class="memTopPart">
				<div class="row totalMembership">
					<div class="col-md-6 col-sm-6 col-12 typeOne typeOneSpecial"></div>
					<div class="col-md-3 col-sm-3 col-12 typeTwo typeTwoSpecial">
						<div class="topLeftTab <?php echo ($current_plan['current_membership_plan_id'] != 4) ? "topLRnobtn1":""; ?>">
							<h6 class="default_black_bold">Základní</h6>
							<p>Vždy zdarma</p> 
							<div class="topLeftTabBtn"><?php if ($current_plan['current_membership_plan_id'] == 4){?>
							<button class="btn default_btn membership_red_btn btn-upgrade-downgrade" id="membership_1" data-id="1" plan="Free" ><?php echo $this->config->item('downgrade_membership_btn_text') ?></button>
							<?php }else{ ?>
							<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>
							<?php } ?></div>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-12 typeThree typeThreeSpecial">							
						<div class="topRightTab <?php echo ($current_plan['current_membership_plan_id'] != 1) ? "topLRnobtn1":""; ?>">
							<h6 class="default_black_bold">Perfektní</h6>
							<p><span>0</span>Kč/měs</p>
							<div class="topRightTabBtn"><?php if ($current_plan['current_membership_plan_id'] == 1){?>
							<button class="btn default_btn blue_btn btn-upgrade-downgrade" id="membership_4" data-id="4" plan="Gold"><?php echo $this->config->item('upgrade_membership_btn_text') ?></button>
							<?php }else{ ?>
							<button class="btn default_btn visible_btn noPointer">Aktuálně zvolené</button>
							<?php } ?></div>
						</div>
					</div>
				</div>
			</div>
			<!-- Subpart Body End -->
		</div>
	</div>
	<!-- Mebership 1st Step End -->
	<!-- Mebership 2nd Step Start -->
	<!-- Referral Program Start -->
	<div class="refProgram">
		<div class="row">
			<div class="col-md-12 col-sm-12 col-12">VŽDY MYSLÍME NA ÚSPĚCH NAŠICH UŽIVATELŮ</div>
		</div>
	</div>
	<!-- Referral Program End -->
	<!-- Mebership 2nd Step End -->
	<!-- Mebership 3rd Step End -->
	<div class="membershipTextBody">
		<div class="membershipText">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-12 membershipTextLeft">
					<div class="mshipLeft_section">
						<h2>O TRAVAI ČLENSTVÍ</h2>
						<div class="mshipLeft">
							<h5>Jaká členství aktuálně <span class="display-inline-block">nabízíme ?</span></h5>
							<p>Máme dva druhy členství. Při dokončení registrace a založení účtu každý uživatel získá Základní členství. Toto členství může zdarma povýšit na Perfektní členství, které nabízíme za <span class="display-inline-block">0 Kč</span> měsíčně.</p>
						</div>
						<div class="mshipLeft">
							<h5>Jaký je rozdil mezi Základním a Perfektním <span class="display-inline-block">členstvím ?</span></h5>
							<p>Perfektní členství je rozšíření Základního členství, které uživatelům poskytuje přístup ke stejným funkcím, které mají předplacené v Základním členství, s vyššími limity a lepšími podmínkami.</p>
							<p>Členům Perfektního členství nejenže nabízíme více volných slotů pro ukládání návrhů inzerátů a otevřené inzeráty, ale získávají více příležitostí k lepšímu zviditelnění svých projektů (zahrnuté v aktualizacích projektů každý měsíc + zasílání pozvánek na vytvořené inzeráty), které lze převést do více kvalitativních nabídek/žádostí. Také získáte příležitost k lepšímu zviditelnění svého profilu na stránce Seznam odborníků (může se promítnout do více příležitostí, jak se prezentovat), a vyšší šance vydělat více ze sítě doporučení (nejen získají lepší % výnos, ale také získáváte přístup k výnosům generovaným 2. úrovní vaší sítě doporučení).</p>
							<p>Při zvolení Perfektního členství získáte jednorázový bonus <span class="display-inline-block">10 000 Kč,</span> který můžete utratit za nákupy vylepšení vašich inzerátů na portálu.</p>
						</div>
						<div class="mshipLeft">
							<h5>Jak je možné změnit členství ze Základního na <span class="display-inline-block">Perfektní ?</span></h5>
							<p>Vše, co musíte udělat, je kliknout na tlačítko „Vybrat“ u sloupce Perfektní členství přístupného výše na této stránce. Souhlasit s našimi smluvními podmínkami a kliknout na tlačítko "Vybrat".</p>
						</div>
						<div class="mshipLeft">
							<h5>Je možné snížit členství z Perfektní zpět na <span class="display-inline-block">Základní ?</span> Na co si při změně dát <span class="display-inline-block">pozor ?</span></h5>
							<p>Provedení snížení členství z Perfektní na Základní je kdykoli možné. Avšak je velmi důležité si uvědomit, jaké funkce nejvíce používáte, a tím i jaká omezení touto volbou sami sobě způsobíte.</p>
							<p>Před pokračováním v této volbě je důležité si nejprve přečíst uvedenou tabulku srovnání, charakteristiky všech členství a až poté, pokud to skutečně považujete za nejlepší rozhodnutí pro vás, pokračujte v provedení volby.</p>
						</div>
						<div class="mshipLeft">
							<h5>Jak dlouho trvá každé <span class="display-inline-block">členství ?</span></h5>
							<p>Žádné členství aktuálně není nijak časově omezené.</p>
						</div>
						<div class="mshipLeft">
							<h2>O uživatelském profilu</h2>
							<h3>TOP umístění v žebříčku seznamu odborníků</h3>
							<p>Obecně platí, že VŠECHNY profily předplatitelů Perfektního členství se řadí nad profily v Základním členství.</p>
						</div>
						<div class="mshipLeft">
							<h5>Výběr posykotvaných odborných činností</h5>
							<p>Pro vytvoření úspěšného profilu je nutné vybrat správné oblasti odborností, které definují vaše profesionální dovednosti.Váš profil se zobrazí ve výsledcích vyhledávání, když návštěvníci vyhledávají na základě klíčových slov nebo filtrů v rámci těchto konkrétních kategorií a podkategorií</p>
							<p>Uživatelé čerpající bezplatné členství mohou svůj profil uvést ve 3 kategoriích a 3 podkategoriích, zatímco uživatelé čerpající Perfektní členství mohou používat až 9 kategorií.</p>
						</div>
						<div class="mshipLeft">
							<h5>Uváděné dovednosti a poskytované služby</h5>
							<p>Pro vytvoření úspěšného profilu je dobré uvést seznam dovedností a služeb, které nabízíte.</p>
							<p>Uživatelé mají možnost uvést seznam svých dovedností na svém profilu. Dovednosti lze považovat za klíčová slova, která popisují nejlepší schopnosti a dovednosti a jsou zvažované jako důležitý faktorv našem vyhledávacím algoritmu. Čím více dovedností uživatel uvání na svém profilu, tím vyšší je šance, že se jeho profil objeví ve výsledcích vyhledávání, když návštěvníci budou provádět vyhledávání na základě relevantních slov.</p>
							<p>Uživatelé čerpající Základní členství mohou uvést až 5 dovedností, zatímco uživatelé Perfektního členství mohou uvést až 25 dovedností.</p>
						</div>
						<div class="mshipLeft">
							<h5>Vlastní titulní grafika profilu</h5>
							<p>Grafika bude vždy na prvním místě, která jasně seznamuje návštěvníky s jednotlivcem nebo značkou. Na Travai je proto možné na svůj profil nejen vložit profilovou fotku (logo), ale také vlastní profilovou grafiku, která za vás mluví s návštěvníky profilu. Nahrání profilové grafiky je jednoduché, je ale zapotřebí mít nastavené Perfektní členství, a tím čerpat tyto výhody.</p>
						</div>
						<div class="mshipLeft">
							<h5>Počet portfolií</h5>
							<p>Za každého odborníka nejvíce hovoří práce, proto nabízíme sekci "Protfolio" jako součást každého uživatelského profilu. Proto uveďte všechny vaše realizované projekty a vytvořte nejlepší dojem a případně budujte dobrou pověst a svoji značku. V Základním členství je možné úvést na svém profilu 5 portofolií. Doporučujeme čerpat výhod Perfektního členství, kde je možné uvést na svém profilu až 15 portfolií</p>
						</div>
						<div class="mshipLeft">
							<h5>Počet obrázků v jednom portfoliu</h5>
							<p>V Základním členství je možné uvádět pro každé portfolio až 5 fotografií, kdežto při čerpání výhod Perfektního členství může každý uživatel přiložit až 15 fotografií v každém svém portfoliu.</p>
						</div>
						<div class="mshipLeft">
							<h5>Vlastní grafika portfolia</h5>
							<p>Jakoukoli prezentaci odborníka či společnost doprovází firemní logo, firemní barvy a propracovaná grafika. Na Travai jsme si tohoto vědomi a proto je možné při vytváření portfolia, přiložit vlastní grafiku pro všechny druhy členství. Grafické zpracování tímto vystihuje jak samotnou stránku portfolia, tak doprovází samotnou práci s kooperací vlastní značky, která jasně mluví s návštěvníky profilu.</p>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-12 membershipTextRight">
					<div class="mshipRight_section">
						<h2>O INZERÁTECH</h2>
						<div class="mshipRight">
							<h5>Zveřejňování inzerátů</h5>
							<p>Na Travai existují 2 typy inzerátů.</p>
							<p><strong>Inzerát projektu</strong></p>
							<p>Způsob, jakým chcete platby projektu spravovat, rozdělujeme inzeráty na:</p>
							<p>- projekty s fixním rozpočtem (je stanovený rozpočet při zadání inzerátu nebo dohodnutý před zahájením práce)</p>
							<p>- projekty s hodinovou sazbou (využité u situace kdy si nemůžete být jistí celkovou částkou realizace nebo při práci na nestandardních pracích na projektech, pro které ještě nemáte definovaný plný rozsah)</p>
							<p><strong>Inzerát pracovní pozice</strong></p>
							<p>Inzeráty pro pracovní pozice jsou určené pro zaměstnavatele, kteří hledají zaměstnance na jakýkoliv typ úvazku</p>
							<p>Vytvoření libovolného typu inzerátu je ZDARMA.</p>
						</div>
						<div class="mshipRight">
							<h5>Počet inzerátů projektů a pracovních pozic ke zveřejnění za měsíc</h5>
							<p>Travai uživatelé mohou vytvořit neomezené množství inzerátů za měsíc, ať se jedná o inzeráty jakýchkoli projektů nebo pracovních pozic na libovolný typ pracovního úvazku a brigád.</p>
						</div>
						<div class="mshipRight">
							<h5>Počet volných slotů</h5>
							<p>Slot je jednotka pro jeden uložený návrh inzerátu nebo jeden otevřený inzerát.</p>
							<p>Matematicky vyjádřeno: 1 slot = 1 návrh inzerátu nebo 1 slot = 1 otevřený inzerát</p>
							<p>Každé členství obsahuje určitý počet otevřených slotů. Sloty jsou rozdělené pro každý typ inzerátu na inzeráty projektů a inzeráty pracovních pozic.</p>
							<p>V Základním členství uživatelé mají k dispozici 5 slotů pro uložení návrhu projektů jako koncept a stejný počet slotů je k dispozici pro ukládání návrhu inzerátu pracovních pozic jako koncept. Tímto znamená, že každý uživatel mající Základní členství může mít kdykoli uloženo 5 návrhů projektů a dalších 5 návrhů pracovních pozic jako koncepty.</p>
							<p>Pro zveřejněné inzeráty je nabídnuto v Základním členství k dispozici také až 5 slotů pro inzeráty projektů a stejný počet slotů pro inzeráty pracovních pozic. Tímto znamená, že každý uživatel čerpající Základní členství může mít kdykoli zveřejněných 5 projektů a dalších 5 pracovních pozic v aktuálním čase</p>
							<p>Pro uživatele čerpající Perfektní členství jsou podmínky naprosto stejné jen s rozdílem navýšení počtu volných slotů. Tedy volné sloty pro návrhy inzerátů a otevřené inzeráty je k dispozici 50 slotů na místo 5 slotů.</p>
						</div>
						<div class="mshipRight">
							<h5>Jak se uvolní <span class="display-inline-block">slot ?</span></h5>
							<p>Aby bylo možné uvolnit použitý slot, je třeba provést několik samostatných akcí:</p>
							<p>- pro návrhy inzerátů buď smazat několik těchto návrhů, kolik slotů potřebujete uvolnit nebo některý návrh použít pro zveřejnění inzerátu</p>
							<p>- uvolnění slotů pro otevřené (zveřejněné) inzeráty postačí některý tento inzerát stornovat</p>
							<p>- počkat až vyprší platnost inzerátu (možnost je k dispozici pouze pro inzeráty pracovních pozic) nebo udělit inzeráty a zahájit spolupráci přes portál (tím se změní status inzerátu z "otevřený" na "probíhající" a uvolní se slot)</p>
						</div>
						<div class="mshipRight">
							<h5>Počet přijatých nabídek na všechny typy inzeratů</h5>
							<p>Neomezené.</p>
						</div>
						<div class="mshipRight">
							<h5>Neomezený přístup k funkci Travai Bezpečná Platba</h5>
							<p>Naše platební funkce Travai Bezpečná Platba je dostupná pro všechny inzeráty (projekty a pracovní pozice), které jsou realizované přes portál. Travai bezpečná platba je součástí našeho systému pro správu plateb na projekty a pracovny pozice, pro potřebu všech zaměstnavatelů a dodavatelů služeb a vhodnou součástí každého obchodu.</p>
							<p>Tato funkce pomáhá chránit při obchodu, eliminovat potentialní chyby a zpronevěření peněz.</p>
							<p>Zaměstnavatelé při využití této funkce si mohou být jistí, že zaplatí svému dodvateli služeb za správně dodané služby a produkty v ten moment, kdy je vše dodané podle dohody.</p>
							<p>Podobně i dodavatelé služeb při využití této funkce si mohou být jistí, že dostanou zaplaceno od svého zaměstnavatele, za správně dodané služby a produkty v ten okamžik, kdy je vše dodáno podle dohody.</p>
							<p>Společně vytváříme poctivé a bezpečné obchody!</p>
						</div>
						<div class="mshipRight">
							<h5>Vylepšení inzerátů</h5>
							<p>Existuje možnost zlepšit viditelnost a důvěrnost inzerátu. Na travai nabízíme 4 různé možnosti vylepšení inzerátu podle vaší potřeby jako: zvýrazněný, urgentní, neveřejný, skryt</p>
							<p>Každé členství zahrnuje určité množství vylepšení zdarma každý měsíc</p>
							<p>Uživatelé čerpající Základní členství mohou zdarma vylepšit 1 zvýrazněný a 1 urgentní inzerát každý měsíc, zatímco uživatelé čerpající Perfektní členství mají k dispozici 3 zvýrazněný, 3 urgentní, 1 neveřejný a 1 skrytý typ vylepšení inzerátu.</p>
							<p>Pokud byly vyčerpány všechny bezplatné vylepšení zahrnuté v daném typu členství, mohou být tyto vylepšení zakoupeny buď z bonusového konta nebo zakoupeny skutečnými penězi.</p>
						</div>
						<div class="mshipRight">
							<h2>DALŠÍ TRAVAI FUNKCE</h2>
							<h5>Kolik je možné mít oblíbených zaměstnavatelů a <span class="display-inline-block">partnerů?</span></h5>
							<p>Travai uživatelé si mohou přidat další uživatele mezi oblíbené, aby mohli získávat informace činností, když zveřejní inzerát. Tedy pokaždé, když jeden z těchto uživatelů ve vašem seznamu zveřejní nový inzerát, budete o tomto procesu okamžitě informováni.</p>
							<p>V základním členství je možné přidat až 5 zaměstnavatelů nebo partnerů. V Perfektním členství je možné přidat neomezeně zaměstnavatelé nebo partnery.</p>
							<p>Seznam inzerátů zveřejněných vaším oblíbeným zaměstnavatelem či partnerem, najdete v sekci „Oznámení inzerátů“</p>
						</div>
						<div class="mshipRight">
							<h5>Oznámení nových inzerátů v reálném čase</h5>
							<p>Každý uživatel (čerpající jakékoli členství), který nastavil stejné odborné činnosti, ve kterých jsou aktuálně zveřejňované inzeráty nebo tyto inzeráty jsou vytvořené oblíbenými uživateli, obdrží ihned oznámení a může okamžitě inzeráty číst, reagovat na ně, komunikovat.</p>
						</div>
						<div class="mshipRight">
							<h5>Úrovně příjmů z doporučení</h5>
							<p>Na základě čerpání členství, dostávají uživatelé aktuálně generovaný příjem ze 2 úrovní v případě Perfektního členství nebo pouze z 1. úrovně Základního členství.Více informací o referenčním programu naleznete zde a způsob jakým vás dokážeme odměnit zveřejňujeme v tabulce s typy členství a úrovních pozvání.</p>
						</div>
						<div class="mshipRight">
							<h5>Poplatky za platby přes portál</h5>
							<p>Považujeme všechny naše uživatele za naše partnery. Na Travai budete platit servisní poplatky pouze ve chvíli, kdy jste spokojeni s dodáním kvality služby nebo produktu. v případě neúspěšné nebo nedokončené spolupráce nevidíme žádný důvod, proč byste platili jakékoli poplatky. Toto je oboustranně výhodná spolupráce, kterou nabízíme našim uživatelům. Úroveň poplatků za služby se liší podle členství. Přesné výše poplatků jsou uvedené v přehledu.</p>
						</div>
						<div class="mshipRight">
							<h5>Posílání pozvání na svůj inzerát</h5>
							<p>jako vlastník projektu nebo zaměstnavatel máte možnost vybrat a pozvat do svého projektu odborníky, které považujete za nejvíce kvalifikované pro vaši potřebu. Při čerpání Základního členství je možné poslat 10 pozvání z toho 3 pozvání na každý vytvořený inzerát v období jednoho měsíce. V Perfektním členství je možné vytvořit až 100 pozvání (maximálně 5 na jeden projekt) v období jednoho měsíce.</p>
						</div>
						<div class="mshipRight">
							<h5>Odesílání a přijímání žádostí o spojení</h5>
							<p>Travai je profesionální sociální komunita, kde uživatelé mohou komunikovat s ostatními, sdílet informace, s konečným cílem nabídnout všem šanci na nalezení úspěchu. Neexistujeme žádná omezení, pokud jde o počet kontaktů, které může mít uživatel, množství vyměněných zpráv nebo nebo cokoliv dalšího. Uživatelé mohou odesílat a přijímat neomezený počet žádostí o kontakt, bez ohledu na typ členství.</p>
							<p>O tom, jak tento proces řídit, rozhodují pouze naši uživatelé.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Mebership 3rd Step End -->
	<!-- Mebership 4th Step Start -->
	<div class="membershipContact">
		<h3>JSME TADY K DISPOZICI</h3>
		<p><span class="display-inline-block">Chcete od nás prezentovat jednotlivá specifika nebo každé členství, a tím porozumět, které může více vyhovovat vašim potřebám?</span> <span class="display-inline-block">Zavolejte nebo pošlete zprávu a my vám zavoláme zpět.</span></p>
		<div class="membershipContactBtn">
			<button class="btn default_btn transparent_btn"><?php echo $this->config->item('presentation_pages_phone_btn_txt'); ?></button><button class="btn default_btn transparent_btn write_a_message"><?php echo $this->config->item('presentation_pages_contact_us_btn_txt'); ?></button>
		</div>
	</div>
	<!-- Mebership 4th Step End -->
</div>

<script type="text/javascript">
	var baseurl = '<?php echo  base_url()?>';
	var popup_success_heading = "<?php echo $this->config->item('popup_success_heading'); ?>";
	//var downgrade_plan_swal_txt = '<?php echo $this->config->item('downgrade_plan_swal_txt')?>';
	//var downgrade_plan_disclamer = '<?php echo $this->config->item('downgrade_plan_disclamer')?>';
	var downgrade_btn_text = '<?php echo $this->config->item('downgrade_membership_btn_text')?>';
	//var downgrade_plan_title = '<?php echo $this->config->item('downgrade_plan_title')?>';
	var successful_downgrade_gold_to_free_membership_confirmation_popup = '<?php echo $this->config->item('successful_downgrade_gold_to_free_membership_confirmation_popup')?>';
	var successful_upgrade_free_to_gold_membership_confirmation_popup = '<?php echo $this->config->item('successful_upgrade_free_to_gold_membership_confirmation_popup')?>';
</script>
<!--
<div class="modal alert-popup" id="success_popup" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
		
	    <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close popup_close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			  <div class="modal-header-inner">
				<h4 class="modal-title" id="success_popup_heading"></h4>
			  </div>
			</div>
			<div class="modal-body text-center">
			  <p id="success_popup_body"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default_btn red_btn popup_close" data-dismiss="modal">Close</button>
			</div>
	    </div>
	</div>
</div>
-->
<!--
<div class="modal fade alert-popup" id="success_popup" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text" style="border:#3c763d;">
				<h4 class="modal-title popup_header_title" id="success_popup_heading"><?php //echo $this->config->item('downgrade_plan_title')?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="confirmation_modal_body">
				<div class="popup_body_semibold_title" id="success_popup_body"><?php echo $this->config->item('downgrade_plan_txt'); ?></div><div class="row"><div class="col-md-12"></div></div>
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-12" id="confirmation_modal_footer">
						<button type="button" class="btn red_btn default_btn" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
-->
<div class="modal alert-popup" id="success_popup" role="dialog">
	<div class="modal-dialog modal-dialog-centered">
	  <!-- Modal content-->
		<div class="modal-content">
			
			<div class="modal-header">
			  <button type="button" class="close popup_close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
			  <!--
			  <div class="modal-header-inner">
				<h4 class="modal-title" id="error_popup_heading"></h4>
			  </div>-->
			</div>
			<div class="modal-body text-center">
			  <p id="success_popup_body"></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn default_btn red_btn popup_close" data-dismiss="modal"><?php
				echo $this->config->item('close_btn_txt'); ?></button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Start for confirm downgrade upgrade-->
<!--
<div class="modal fade" id="downgradePlanConfirmationModal" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header">
				<h5 class="modal-title"><?php echo $this->config->item('downgrade_membership_plan_title')?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="cancel_project_modal_body">
			<?php 
			echo $this->config->item('downgrade_membership_plan_txt').'</br></br><label for="disclamer"><input type="checkbox" id="disclamer" >'.$this->config->item('downgrade_plan_disclamer').'</label>';
			?>
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-xs-12">
						<button type="button" class="btn btnCancel downgrade_proceed_button width-auto" disabled>Proceed</button>
						<button type="button" class="btn btnSave" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
-->

<div class="modal fade" id="downgradePlanConfirmationModal" tabindex="-1" role="dialog"  aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content etModal">
			<div class="modal-header popup_header popup_header_without_text">
				<h4 class="modal-title popup_header_title" id="confirmation_modal_title"></h4>
				<button type="button" class="close close_downgrade_popup" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body most-project" id="confirmation_modal_body">
				<div class="popup_body_semibold_title"><?php echo $this->config->item('downgrade_membership_plan_txt'); ?></div><div class="row"><div class="col-md-12"><div class="radio_modal_separator"><label class="default_checkbox"><input type="checkbox" class="receive_notification" id="disclamer"><span class="checkmark"></span><span class="chkText popup_body_regular_checkbox_text"><?php echo $this->config->item('downgrade_membership_plan_disclamer'); ?></span></label></div></div></div>
			</div>
			<div class="modal-footer mg-bottom-10">
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-12" id="confirmation_modal_footer">
						<button type="button" class="btn red_btn default_btn close_downgrade_popup default_popup_width_btn" data-dismiss="modal"><?php echo $this->config->item('cancel_btn_txt'); ?></button>&nbsp;<button type="button" disabled  class="btn blue_btn default_btn downgrade_proceed_button width-auto default_popup_width_btn"><?php echo $this->config->item('downgrade_membership_popup_proceed_btn_text'); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal End -->