<?php
class quiz_ActionBase extends f_action_BaseAction
{
		
	/**
	 * Returns the quiz_PreferencesService to handle documents of type "modules_quiz/preferences".
	 *
	 * @return quiz_PreferencesService
	 */
	public function getPreferencesService()
	{
		return quiz_PreferencesService::getInstance();
	}
		
	/**
	 * Returns the quiz_QuizService to handle documents of type "modules_quiz/quiz".
	 *
	 * @return quiz_QuizService
	 */
	public function getQuizService()
	{
		return quiz_QuizService::getInstance();
	}
		
	/**
	 * Returns the quiz_QuestionService to handle documents of type "modules_quiz/question".
	 *
	 * @return quiz_QuestionService
	 */
	public function getQuestionService()
	{
		return quiz_QuestionService::getInstance();
	}
		
	/**
	 * Returns the quiz_ResponseService to handle documents of type "modules_quiz/response".
	 *
	 * @return quiz_ResponseService
	 */
	public function getResponseService()
	{
		return quiz_ResponseService::getInstance();
	}
		
	/**
	 * Returns the quiz_ParticipantService to handle documents of type "modules_quiz/participant".
	 *
	 * @return quiz_ParticipantService
	 */
	public function getParticipantService()
	{
		return quiz_ParticipantService::getInstance();
	}
		
}