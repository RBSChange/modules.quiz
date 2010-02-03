<?php
class quiz_QuizService extends f_persistentdocument_DocumentService
{
	/**
	 * @var quiz_QuizService
	 */
	private static $instance;

	/**
	 * @return quiz_QuizService
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
	 * @return quiz_persistentdocument_quiz
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_quiz/quiz');
	}

	/**
	 * Create a query based on 'modules_quiz/quiz' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_quiz/quiz');
	}

	/**
	 * @param quiz_persistentdocument_quiz $document
	 * @param Integer $parentNodeId Parent node ID where to save the document.
	 * @return void
	 */
	protected function postUpdate($document, $parentNodeId = null)
	{
		if ($document->getUsebutton() != $document->getUsebuttonOldValue())
		{
			// Get children question
			$questions = $document->getChildrenQuestion();
			
			foreach ($questions as $question)
			{
				$question->updateUseButton($document->getUsebutton());
			}
		}
	}

	/**
	 * @param quiz_persistentdocument_quiz $document
	 * @return boolean true if the document is publishable, false if it is not.
	 */
	public function isPublishable($document)
	{
		if (parent::isPublishable($document))
		{
			$query = quiz_QuestionService::getInstance()->createQuery()
				->add(Restrictions::published())
				->add(Restrictions::childOf($document->getId()))
				->setProjection(Projections::rowCount('count'));
			$result = $query->find();
			if ($result[0]['count'] > 0)
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * Add a participant to a quiz
	 *
	 * @param quiz_persistentdocument_quiz $quiz
	 * @param array $data
	 */
	public function addParticipant($quiz, $data)
	{
		try 
		{
			$tm = $this->getTransactionManager();
			$tm->beginTransaction();
						
			// Create a participant
			$participant = quiz_ParticipantService::getInstance()->getNewDocumentInstance();
			$participant->setLabel($data['email']);
			$participant->setQuiz($quiz);
			$participant->setAllright($data['allright']);
			$participant->setTitle($data['title']);
			$participant->setLastname($data['lastname']);
			$participant->setFirstname($data['firstname']);
			$participant->setEmail($data['email']);
			$participant->setAddress($data['address']);
			$participant->setCity($data['city']);
			$participant->setPostalcode($data['postalcode']);
			$participant->setCountry($data['country']);
			$participant->save();
			
			$tm->commit();
	    }
		catch (Exception $e)
		{
			$tm->rollBack($e);
		}
	}
	
	/**
	 * @param quiz_persistentdocument_quiz $quiz
	 * @param array $userResponses
	 */
	public function setUserResponses($quiz, $userResponses)
	{
	    $viewGoodResponse = $quiz->getViewgoodresponse();
	    foreach ($quiz->getPublishedQuestions() as $question) 
	    {
	        if (array_key_exists($question->getid(), $userResponses))
	        {
	             $value = $userResponses[$question->getid()];
	             if (!is_array($value))
	             {
	                 if (is_numeric($value))
	                 {
	                     $value = array($value => true);
	                 } 
	                 else 
	                 {
	                     $value = array();
	                 }
	             }
	        }
	        else
	        {
	            $value = array();             
	        }
            foreach ($question->getPublishedResponses() as $response) 
            {
                $response->setViewGoodResponse($viewGoodResponse);
                if (array_key_exists($response->getid(), $value))
                {
                    $response->setSelectedByUser(true);
                }
                else
                {
                    $response->setSelectedByUser(false);
                }
            }
	    }
	}
}