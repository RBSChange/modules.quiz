<?php
class quiz_ParticipantService extends f_persistentdocument_DocumentService
{
	/**
	 * @var quiz_ParticipantService
	 */
	private static $instance;

	/**
	 * @return quiz_ParticipantService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return quiz_persistentdocument_participant
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_quiz/participant');
	}

	/**
	 * Create a query based on 'modules_quiz/participant' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_quiz/participant');
	}

}