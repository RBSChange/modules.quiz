<?php
class quiz_persistentdocument_response extends quiz_persistentdocument_responsebase 
{
    
	/**
	 * @param string $moduleName
	 * @param string $treeType
	 * @param array<string, string> $nodeAttributes
	 */	
	protected function addTreeAttributes($moduleName, $treeType, &$nodeAttributes)
	{
	    $nodeAttributes['correct'] = $this->getIscorrect() ? 'correct' : '';
	}
	
	
	private $selectedByUser = false;
	
	/**
	 * @param Boolean $selected
	 */
	public function setSelectedByUser($selected)
	{
	   $this->selectedByUser = $selected;
	}
	
	/**
	 * @return Boolean
	 */
	public function isSelectedByUser()
	{
	   return $this->selectedByUser;
	}	
	
	private $viewGoodResponse = false;
	
	/**
	 * @param Boolean $selected
	 */
	public function setViewGoodResponse($viewGoodResponse)
	{
	   $this->viewGoodResponse = $viewGoodResponse;
	}	
	
	/**
	 * @return Boolean
	 */
	public function isVisibleInResult()
	{
	   return $this->selectedByUser || ($this->getIscorrect() && $this->viewGoodResponse);
	}		
}