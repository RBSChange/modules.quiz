<?php
class quiz_PreferencesScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return quiz_persistentdocument_preferences
     */
    protected function initPersistentDocument()
    {
    	$document = ModuleService::getInstance()->getPreferencesDocument('quiz');
    	return ($document !== null) ? $document : quiz_PreferencesService::getInstance()->getNewDocumentInstance();
    }
}