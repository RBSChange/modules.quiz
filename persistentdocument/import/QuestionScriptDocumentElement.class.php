<?php
class quiz_QuestionScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return quiz_persistentdocument_question
     */
    protected function initPersistentDocument()
    {
    	return quiz_QuestionService::getInstance()->getNewDocumentInstance();
    }
}