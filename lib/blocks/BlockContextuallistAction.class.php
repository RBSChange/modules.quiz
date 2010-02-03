<?php
class quiz_BlockContextuallistAction extends abstractdirectory_BlockContextuallistAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('quiz');
		$this->setComponentName('quiz');
	}
}