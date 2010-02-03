<?php
/**
 * @date Tue, 20 Nov 2007 15:59:06 +0100
 * @author intcoutl
 */
class quiz_PreferencesService extends abstractdirectory_PreferencesService
{
	/**
	 * @var quiz_PreferencesService
	 */
	private static $instance;

	/**
	 * @return quiz_PreferencesService
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
	 * @return quiz_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_quiz/preferences');
	}

	/**
	 * Create a query based on 'modules_quiz/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_quiz/preferences');
	}
	
	/**
	 * @param quiz_persistentdocument_preferences $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId = null)
	{
		$document->setLabel('&modules.quiz.bo.general.Module-name;');
	}
}