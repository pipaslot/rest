<?php


namespace Pipas\Rest\Debug;

use Nette\Object;
use Tracy\IBarPanel;

/**
 * @author Petr Štipek <p.stipek@email.cz>
 */
class RestPanel extends Object implements IBarPanel
{
	/**
	 * Renders HTML code for REST tab.
	 * @return string
	 */
	function getTab()
	{
		ob_start();
		require __DIR__ . "/assets/tab.phtml";
		return ob_get_clean();
	}

	/**
	 * Renders HTML code for REST panel.
	 * @return string
	 */
	function getPanel()
	{
		if (count(Logger::getList()) == 0) return null;
		ob_start();
		require __DIR__ . "/assets/panel.phtml";
		return ob_get_clean();
	}
}