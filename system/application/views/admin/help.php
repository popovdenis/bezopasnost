<!-- HelpBox Section -->
<div id="help">
	<div id="panel">
		<div id="helpBoxAdmin">
			<div class="helpContentAdmin adminHelp">
				<div class="questionsBlockAdmin">
					<span><img src="<?=base_url()?>images/help-icon.jpg" />Секция Вопросов</span>
					<div id="admin_questions_block" class="questionsBox"></div>
				</div>
				<div id="helpArrowBlock" class="helpArrowBlock">
					<img class="verticalMiddle" src="<?=base_url()?>images/help-green-arrow.jpg" id="image_qa_separatior" />
				</div>
				<div class="answerBlockAdmin">
					<span>Секция Ответов:</span>
					<div id="admin_answers_block" class="answerBox"></div>
				</div>
				<div class="clear"></div>
				<div class="answerAndQuestionBlockAdmin">
					<div class="answerAndQuestionBoxAdmin left">
						<span class="Txt67">Вопрос:</span>
						<div id="question_block"><textarea name="" id="admin_question" cols="" rows=""></textarea></div>
					</div>
					<div class="answerAndQuestionBoxAdmin right">		
						<span class="Txt67">Ответ:</span>
						<div id="answer_block"><textarea name="" id="admin_answer" cols="" rows=""></textarea></div>
					</div>
					<div class="clear"></div>
					<div class="helpSelectButton">
						<div class="helpFilterSelect"><span>Сортировать по:</span>
							<select onchange="filter_qeustions();" id="help_pages_filter">
								<option value="0">имя страницы</option>
								<option value="general">общие вопросы</option>
								<option value="about">О Компании</option>
								<option value="information">Информация</option>
								<option value="partners">Партнеры</option>
								<option value="products">Продукция</option>
								<option value="contats">Контакты</option>
								<option value="settings">Настройки</option>
							</select>
						</div>
						<div class="saveButtHelpAdmin">
							<a onclick="save_records();" href="#" class="button"><span>Сохранить</span></a>
						</div>				
						<img onclick="clear_all();" title="New" src="<?=base_url()?>images/big-plus.gif" style="cursor: pointer;" class="right" alt="">
						<span>Вопрос в раздел:</span>
						<select id="help_pages">
							<option value="0">имя страницы</option>
								<option value="general">общие вопросы</option>
								<option value="about">О Компании</option>
								<option value="information">Информация</option>
								<option value="partners">Партнеры</option>
								<option value="products">Продукция</option>
								<option value="contats">Контакты</option>
								<option value="settings">Настройки</option>
						</select>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<input id="help_action" type="hidden" value="open" />
	<input id="help_question_id" type="hidden" value="" />
	<p class="slide"><a href="#" class="btn-slide" onclick="get_records();">HELP</a></p>
</div>
<!-- / HelpBox Section -->