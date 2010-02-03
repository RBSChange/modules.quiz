<?php
class quiz_persistentdocument_participant extends quiz_persistentdocument_participantbase implements export_ExportableDocument
{
	/**
	 * Get the exported document
	 *
	 * @return export_ExportedDocument
	 */
	public function getExportedDocument()
	{
		$exportedDoc = new export_ExportedDocument();
		$exportedDoc->setProperty('quiz', $this->getQuiz()->getLabel());
		$exportedDoc->setProperty('correct', $this->getAllright() == true ? f_Locale::translate('&modules.quiz.bo.general.export.Ok;') : f_Locale::translate('&modules.quiz.bo.general.export.Nok;'));
		$exportedDoc->setProperty('title', $this->getTitle());
		$exportedDoc->setProperty('lastname', $this->getLastname());
		$exportedDoc->setProperty('firstname', $this->getFirstname());
		$exportedDoc->setProperty('email', $this->getEmail());
		$exportedDoc->setProperty('address', $this->getAddress());
		$exportedDoc->setProperty('postalcode', $this->getPostalcode());
		$exportedDoc->setProperty('city', $this->getCity());
		$exportedDoc->setProperty('country', $this->getCountry());
		$exportedDoc->setLang(RequestContext::getInstance()->getLang());
		return $exportedDoc;
	}
}