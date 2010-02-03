<?php
class quiz_BlockQuizListAction extends abstractdirectory_BlockListAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('quiz');
		$this->setComponentName('quiz');
	}
}