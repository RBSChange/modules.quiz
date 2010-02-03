<?php
class quiz_persistentdocument_question extends quiz_persistentdocument_questionbase 
{	
	/**
	 * Update the value of usebutton
	 * 
	 * @param Boolean $bool
	 */
	public function updateUseButton($bool = false)
	{
		$this->getDocumentService()->updateUseButton($this, $bool);
	}
	

	private $publishedResponses;
	
	public function getPublishedResponses()
	{
	    if ($this->publishedResponses === null)
	    {
	        $this->publishedResponses = $this->getChildrenPublishedResponse();
	        
	    }
	    return $this->publishedResponses;
	}

	/**
	 * @return boolean
	 */
	public function hasUserResponse()
	{
	    foreach ($this->getPublishedResponses() as $response) 
	    {
	    	if ($response->isSelectedByUser())
	    	{
	    	    return true;
	    	}
	    }
	    return false;
	}

	/**
	 * @return boolean
	 */
	public function hasGoodUserResponse()
	{
	    foreach ($this->getPublishedResponses() as $response) 
	    {
	        if ($response->isSelectedByUser())
	    	{
	    	    if (!$response->getIscorrect())
	    	    {
	    	        return false;   
	    	    }
	    	} 
	    	else if ($response->getIscorrect())
	    	{
	    	    return false;
	    	}
	    }
	    return true;
	}	
}