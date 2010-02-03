<?php
class quiz_QuizScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return quiz_persistentdocument_quiz
     */
    protected function initPersistentDocument()
    {
    	return quiz_QuizService::getInstance()->getNewDocumentInstance();
    }
}