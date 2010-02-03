<?php
class quiz_persistentdocument_quiz extends quiz_persistentdocument_quizbase implements indexer_IndexableDocument, export_ExportableDocument{
	/**
	 * Get the indexable document
	 *
	 * @return indexer_IndexedDocument
	 */
	public function getIndexedDocument()
	{
		$indexedDoc = new indexer_IndexedDocument();
		// TODO : set the different properties you want in you indexedDocument :
		// - please verify that id, documentModel, label and lang are correct according your requirements
		// - please set text value.
		$indexedDoc->setId($this->getId());
		$indexedDoc->setDocumentModel('modules_quiz/quiz');
		$indexedDoc->setLabel($this->getLabel());
		$indexedDoc->setLang(RequestContext::getInstance()->getLang());
		
		$text = $this->getLabel() . ' ' . $this->getDescriptionAsHtml();
		foreach ($this->getChildrenPublishedQuestion() as $question)
		{
			$text .= ' ' . $question->getLabel();
			
			foreach ($question->getChildrenPublishedResponse() as $response)
			{
				$text .= ' ' . $response->getLabel();
			}
			
		}
		$indexedDoc->setText($text);
		
		return $indexedDoc;
	}
	
	/**
	 * Get the exported document
	 *
	 * @return export_ExportedDocument
	 */
	public function getExportedDocument()
	{
		// Get the participant of quiz
		$participants = quiz_ParticipantService::getInstance()->createQuery()
			->add(Restrictions::eq('quiz.id', $this->getId()))
			->addOrder(Order::desc('allright'))
			->find();

		$exportedDoc = new export_ExportedDocument();
		$exportedDoc->setLang(RequestContext::getInstance()->getLang());
		
		foreach ($participants as $participant)
		{
			$exportedParticipant = $participant->getExportedDocument();
			$exportedDoc->addChildProperties($exportedParticipant->getProperties());
		}
		return $exportedDoc;
	}
	
	private $publishedQuestions;
	
	public function getPublishedQuestions()
	{
	    if ($this->publishedQuestions === null)
	    {
	        $this->publishedQuestions = $this->getChildrenPublishedQuestion();
	        
	    }
	    return $this->publishedQuestions;
	}
	
	/**
	 * @return Boolean
	 */
	public function hasAllUserResponses()
	{
	    foreach ($this->getPublishedQuestions() as $question) 
	    {
	    	if (!$question->hasUserResponse())
	    	{
	    	    return false;
	    	}
	    }
	    return true;
	}
	    
	/**
	 * @return Integer
	 */
	public function countGoodUserResponses()
	{
	    $count = 0;
		foreach ($this->getPublishedQuestions() as $question) 
	    {
	    	if ($question->hasGoodUserResponse())
	    	{
	    	    $count++;
	    	}
	    }	    
	    return $count;
	}
}