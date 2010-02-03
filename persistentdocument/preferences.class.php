<?php
/**
 * quiz_persistentdocument_preferences
 * @package quiz
 */
class quiz_persistentdocument_preferences extends quiz_persistentdocument_preferencesbase 
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getLabel()
	 *
	 * @return String
	 */
	public function getLabel()
	{
		return f_Locale::translateUI(parent::getLabel());
	}
}