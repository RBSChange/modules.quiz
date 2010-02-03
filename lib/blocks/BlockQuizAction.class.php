<?php
class quiz_BlockQuizAction extends abstractdirectory_BlockItemAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('quiz');
		$this->setComponentName('quiz');
	}
}