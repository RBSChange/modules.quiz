<?php
class quiz_BlockDetailParticipeView extends block_BlockView
{

	/**
	 * Mandatory execute method...
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 */
    public function execute($context, $request)
    {    	
    	$this->setTemplateName('Quiz-Block-Participe-Success');
    	
    	f_event_EventManager::register($this);

  		$form = form_FormService::getInstance()->getFormByFormId('modules_quiz/participe');

		$subBlock = $this->getNewBlockInstance()
        	->setPackageName("modules_form")
        	->setType("form")
        	->setParameter(K::COMPONENT_ID_ACCESSOR , array($form->getId()));

   	 	$this->setAttribute('form', $this->forward($subBlock));
   	 	
   	 	$quiz = $this->getDocumentParameter();
   	 	$this->setAttribute('formHeader', $quiz->getParticipationheaderAsHtml());
   	 	$this->setAttribute('formFooter', $quiz->getParticipationfooterAsHtml());
    }

 	/**
	 * @param form_FormService $sender
	 * @param array<'form' => form_persistentdocument_form, 'parameters' => &Array<>>
	 */
    public function onformInitData($sender, $params)
	{		
		$form = $params['form'];			
		$parameters = &$params['parameters'];
	}
	
	/**
	 * @param form_persistentdocument_form $form
	 * @param array<'response' => form_persistentdocument_response> $params
	 */
	public function onformSubmitted($form, $params)
	{		
		$response = $params['response'];
		$datas = $response->getData();
		
		$quiz = $this->getDocumentParameter();
		
		// Save the participant
		$quiz->getDocumentService()->addParticipant($quiz, $datas);
		
		// Send the notification
		$notifService = notification_NotificationService::getInstance();
		$notification = $notifService->getNotificationByCodeName('modules_quiz/participant');
		if(! is_null($quiz->getNotificationsubject()) )
		{
			$notification->setSubject($quiz->getNotificationsubject());
		}
		if(! is_null($quiz->getNotificationheader()) )
		{
			$notification->setHeader($quiz->getNotificationheader());
		}
		if(! is_null($quiz->getNotificationbody()) )
		{
			$notification->setBody($quiz->getNotificationbody());
		}
		if(! is_null($quiz->getNotificationfooter()) )
		{
			$notification->setFooter($quiz->getNotificationfooter());
		}
		
		$recipients = new mail_MessageRecipients();
		$recipients->setTo($datas['email']);
		$notifService->send($notification, $recipients, array(), 'quiz');
		
		$url = LinkHelper::getUrl($quiz, RequestContext::getInstance()->getLang(), array('formParam' => array('ok' => 1, 'data' => $datas['data'])));
	    HttpController::getInstance()->redirectToUrl(str_replace('&amp;', '&', $url));
	}

	/**
	 * @param form_FormService $sender
	 * @param array<'form' => form_persistentdocument_form, 'request' => block_BlockRequest, 'errors' => validation_Errors) $params
	 */
	public function onformValidate($sender, $params)
	{
		$errors = $params['errors'];
		$request = $params['request'];
		
		$email = $request->getParameter('email');
		
		if ($email != "")
		{
			$nbParticipants = quiz_ParticipantService::getInstance()->createQuery()
				->add(Restrictions::eq('email', $email))
				->add(Restrictions::eq('quiz.id', $this->getDocumentParameter()->getId()))
				->setProjection(Projections::rowCount('nb'))
				->find();
		
			if ($nbParticipants[0]['nb'] != 0)
			{
				$errors->rejectValue('', '&modules.quiz.frontoffice.participe.ImpossibleToParticipeAgain;');
			}
		}
		
	}
}