<?php
class quiz_BlockTopicAction extends abstractdirectory_BlockTopicAction
{
	public function initialize($context, $request)
	{
		parent::initialize($context, $request);
		$this->setModuleName('quiz');
		$this->setComponentName('quiz');
	}
}