<?php
class quiz_BlockDetailAction extends block_BlockAction
{
    /**
     * @return quiz_persistentdocument_quiz
     */
    private function getQuiz()
    {
        try 
        {
            $quiz = $this->getDocumentParameter();
            if ($quiz instanceof quiz_persistentdocument_quiz) 
            {
            	return $quiz;
            }
        } 
        catch (Exception $e)
        {
            Framework::exception($e);
        }
        return null;
    }
    
    private $quizpart = array();
    
    public function initialize($context, $request)
    {
        if (isset($_COOKIE['quizpart']))
        {
            $strcart = $_COOKIE['quizpart'];
            if (!empty($strcart))
            {
                $this->quizpart = explode(',', $strcart);
            }
        }
    }
    
    private function setContextQuizpart($quizpart)
    {
        $this->quizpart = $quizpart;
        if (!headers_sent()) 
        {
            setcookie('quizpart', implode(',', $quizpart), time() + 60 * 60 * 24 * 14);
        }
        else
        {
            if (Framework::isWarnEnabled())
            {
                Framework::warn(__METHOD__ . ' not saved');
            }
        }
    }
    
    public function addQuizpart($quizId)
    {
        $id = intval($quizId);
        $quizpart = $this->quizpart;
        if (! in_array($id, $quizpart))
        {
            $quizpart[] = $id;
        }
        $this->setContextQuizpart($quizpart);
    }
    
	/**
     * @param block_BlockContext $context
     * @param block_BlockRequest $request
     * @return String view name
     */
	public function execute($context, $request)
	{	
		if ($context->inBackofficeMode())
		{
			return block_BlockView::DUMMY;
		}
		
		$quiz = $this->getQuiz();
		if ($quiz === null)
		{
		    return block_BlockView::NONE;
		}
		
		$globalRequest = $context->getGlobalRequest();
		if ($globalRequest->hasParameter('formParam'))
		{
			$formParam = $globalRequest->getParameter('formParam');
			if (isset($formParam['ok']))
			{
				$this->setParameter('data', $formParam['data']);
				return 'Participeok';
			}
			else
			{
				return 'Participe';
			}
		}
		
		$this->setParameter('item', $quiz);
		if (!$quiz->getAllowmultiple() && in_array($quiz->getId(), $this->quizpart))
		{
		     $this->addQuizpart($quiz->getId());
		     return 'Filed';   
		}
		
		$questions = $quiz->getPublishedQuestions();	
		$this->setParameter('questions', $questions);
					
		$qs = quiz_QuizService::getInstance();
		
		if ($request->hasParameter('submit') )
		{
		    
		    $requestQuestion = $request->getParameter('question');
		    if (is_array($requestQuestion))
		    {
		        
		        $qs->setUserResponses($quiz, $requestQuestion);
		    }
		    else if ($request->hasParameter('data'))
		    {
		        
		        $requestQuestion = unserialize(urldecode($request->getParameter('data')));
				$qs->setUserResponses($quiz, $requestQuestion);
		    } 
		    else
		    {
		       $requestQuestion = array(); 			    
		    }
		    
            if (!$quiz->hasAllUserResponses())
            {
				$errors = array();
				$errors[] = f_Locale::translate('&modules.quiz.messages.error.AllQuestionMustBeHandled;');
				$this->setParameter( 'errors', $errors );
				return block_BlockView::INPUT;                 
            }
            
            $nbQuestion = count($questions);
			$nbGoodResponse = $quiz->countGoodUserResponses();
				
			// Get the value of process type
			$processType = $quiz->getProcesstype();
			if ($processType != 0 && !$request->hasParameter('data'))
			{
			    $this->addQuizpart($quiz->getId());
				$globalRequest->setParameter('formParam', array('allright' => ($nbQuestion == $nbGoodResponse), 
											'data' => urlencode(serialize($requestQuestion))));
				return 'Participe';
			}
			else 
			{					
				if ($nbGoodResponse > 1)
				{
					$localeKey = "&modules.quiz.frontoffice.result.infoResults;";
				}
				else 
				{
					$localeKey = "&modules.quiz.frontoffice.result.infoResult;";
				}
				
				$this->setParameter('result', f_Locale::translate($localeKey, array('nbResponse' => $nbGoodResponse, 'nbQuestion' => $nbQuestion)));
				$this->setParameter('nbQuestion', $nbQuestion);
				$this->setParameter('nbGoodResponse', $nbGoodResponse);
				$this->setParameter('responses', $requestQuestion);
				
				$this->addQuizpart($quiz->getId());
				return block_BlockView::SUCCESS;
			}
		}
		else
		{
		    $qs->setUserResponses($quiz, array());
		}
		return block_BlockView::INPUT;

	}
}