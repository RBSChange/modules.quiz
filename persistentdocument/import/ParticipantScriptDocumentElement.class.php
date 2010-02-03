<?php
class quiz_ParticipantScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return quiz_persistentdocument_participant
     */
    protected function initPersistentDocument()
    {
    	return quiz_ParticipantService::getInstance()->getNewDocumentInstance();
    }
}