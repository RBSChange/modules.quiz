<?php
class quiz_BlockDetailParticipeokView extends block_BlockView
{

	/**
	 * Mandatory execute method...
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 */
    public function execute($context, $request)
    {    	
    	$this->setTemplateName('Quiz-Block-Participeok-Success');
    	$lang = RequestContext::getInstance()->getLang();
  		$form = form_FormService::getInstance()->getByFormId('modules_quiz/participe');
   	 	$this->setAttribute('formThx', $form->getConfirmMessageAsHtml());
   	 	
   	 	$quiz = $this->getDocumentParameter();
   	 	if( $quiz->getProcesstype() == 2 )
   	 	{
   	 		// Go to page with result
   	 		$this->setAttribute('listResult', 
   	 			LinkHelper::getUrl('quiz', 'ViewDetail', array('lang' => $lang, 'quizParam' => array(K::COMPONENT_ID_ACCESSOR => $quiz->getId(), 'submit' => 'ok', 'data' => $this->getParameter('data')))));
   	 	}
   	 	
   	 	// Link to go to page of list
   	 	$this->setAttribute('listHref', LinkHelper::getUrl('quiz', 'ViewList', array('quizParam' => array(K::COMPONENT_ID_ACCESSOR =>$quiz->getId()), 'lang' => $lang)));
    }
}