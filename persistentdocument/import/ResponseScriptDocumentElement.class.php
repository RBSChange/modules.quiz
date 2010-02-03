<?php
class quiz_ResponseScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return quiz_persistentdocument_response
     */
    protected function initPersistentDocument()
    {
    	return quiz_ResponseService::getInstance()->getNewDocumentInstance();
    }
}