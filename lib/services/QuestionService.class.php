<?php
class quiz_QuestionService extends f_persistentdocument_DocumentService
{
	/**
	 * @var quiz_QuestionService
	 */
	private static $instance;

	/**
	 * @return quiz_QuestionService
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
	 * @return quiz_persistentdocument_question
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_quiz/question');
	}

	/**
	 * Create a query based on 'modules_quiz/question' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_quiz/question');
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
			$parent = $this->getParentOf($document);
			$parent->getDocumentService()->publishIfPossible($parent->getId());
		}
	}
	
	/**
	 * @param quiz_persistentdocument_question $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
	protected function preInsert($document, $parentNodeId = null)
	{
		// Get the value of usebutton from quiz
		$parent = $this->getDocumentInstance($parentNodeId);		
		$document->setUsebutton($parent->getUseButton());
	}

	/**
	 * @param quiz_persistentdocument_question $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
	public function isPublishable($document)
	{
		if (parent::isPublishable($document))
		{
			$query = quiz_ResponseService::getInstance()->createQuery()
				->add(Restrictions::published())
				->add(Restrictions::childOf($document->getId()))
				->setProjection(Projections::property('iscorrect'));

			$result = $query->find();
			
			$responseCorrect = false;
			$responseNotCorrect = false;
			foreach ($result as $response)
			{
				if ($response['iscorrect'] == 1)
				{
					$responseCorrect = true;
				}
				else 
				{
					$responseNotCorrect = true;
				}
				if ($responseCorrect && $responseNotCorrect)
				{
					return true;
				}
			}
		}
		return false;
	}

	/**
	 * Check if a question can use radio button. The condition is that the question has only one correct response.
	 *
	 * @param quiz_persistentdocument_question $question 
	 * @return Boolean
	 */
	private function checkValidityOfUseButton($question)
	{
		// Get correct response
		$query = quiz_ResponseService::getInstance()->createQuery()
				->add(Restrictions::published())
				->add(Restrictions::childOf($question->getId()))
				->add(Restrictions::eq('iscorrect', 1))
				->setProjection(Projections::rowCount('count'));

		$result = $query->find();
		
		if ($result[0]['count'] == 1)
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * Update the value of usebutton
	 *
	 * @param quiz_persistentdocument_question $question 
	 * @param Boolean $bool
	 */
	public function updateUseButton($question, $bool = false)
	{
		if ($bool)
		{
			$bool = $this->checkValidityOfUseButton($question);
		}
			
		$question->setUsebutton($bool);
		if ($question->isModified())
		{
		    try 
		    {
		        $this->tm->beginTransaction();
		        $this->pp->updateDocument($question);
		        $this->tm->commit();
		    }
		    catch (Exception $e)
		    {
		        $this->tm->rollBack($e);
		    }
		}
	}
	
	/**
	 * Check if the usebutton is still valid
	 *
	 * @param quiz_persistentdocument_question $question 
	 */
	public function checkUseButtonAfterChangeOfResponse($question)
	{
		$quiz = $this->getParentOf($question);
		$this->updateUseButton($question, $quiz->getUsebutton());
	}
	
}