<?php
class quiz_ResponseService extends f_persistentdocument_DocumentService
{
	/**
	 * @var quiz_ResponseService
	 */
	private static $instance;

	/**
	 * @return quiz_ResponseService
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
	 * @return quiz_persistentdocument_response
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_quiz/response');
	}

	/**
	 * Create a query based on 'modules_quiz/response' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_quiz/response');
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param String $oldPublicationStatus
	 * @param array $params
	 * @return void
	 */
	protected function publicationStatusChanged($document, $oldPublicationStatus, $params)
	{
		if ($oldPublicationStatus == 'PUBLICATED' || $document->isPublished())
		{
			// Check the question status
			$question = $this->getParentOf($document);
			$questionService = $question->getDocumentService();
			$questionService->publishIfPossible($question->getId());
			
			if ($question->isPublished())
			{
				$questionService->checkUseButtonAfterChangeOfResponse($question);
			}
		}
	}

	/**
	 * @param quiz_persistentdocument_response $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
	protected function postUpdate($document, $parentNodeId = null)
	{
		if ($document->isPublished())
		{
			if ($document->getIscorrect() != $document->getIscorrectOldValue())
			{
				$parent = $this->getParentOf($document);
				$parent->getDocumentService()->publishIfPossible($parent->getId());
			}
		}
	}

}