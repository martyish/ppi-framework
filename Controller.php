<?php
/**
 * @author    Paul Dragoonis <dragoonis@php.net>
 * @license   http://opensource.org/licenses/mit-license.php MIT
 * @copyright Digiflex Development
 * @package   Controller
 */
class PPI_Controller {

    /**
     * The input object
     *
     * @var PPI_Input
     */
	protected $_input = null;

    /**
     * The PPI_View object
     *
     * @var PPI_View
     */
	protected $_view = null;

    /**
     * The constructor
     */
	function __construct () {
		$this->_input = PPI_Helper::getInput();
		$this->_view  = new PPI_View();
		$this->oInput = $this->_input; // Legacy Code
	}

	/**
	 * Perform redirect to internal framework url. Optionally redirect to external host
     *
	 * @param string $p_sURL Optional param for where to redirect to
	 * @param boolean $p_bPrependBase Default is true. If true will prepend the framework's base Url.
 	 *									If false will redirect to absolute external url.
 	 * @throws PPI_Exception
     * @return boolean
	 */
	protected function redirect($p_sURL = '', $p_bPrependBase = true) {
		$sUrl = ($p_bPrependBase === true) ? $this->getConfig()->system->base_url . $p_sURL : $p_sURL;
		if($this->getCurrUrl() == $sUrl) {
			return false;
		}
		if(!headers_sent()) {
			header("Location: $sUrl");
			exit;
		} else {
			throw new PPI_Exception('Unable to redirect to '.$sUrl.'. Headers already sent');
		}
	}

	/**
	 * Load a view
	 *
	 * @param string $p_tplFile The view filename. File extensions are optional.
	 * @param array $p_tplParams Optional parameters to the view file.
	 * @return void
	 */
	protected function load($p_tplFile, $p_tplParams = array()) {
		$this->_view->load($p_tplFile, $p_tplParams);
	}

	/**
	 * Set a view variable or a list of view variables.
	 *
	 * @param mixed $p_mKeys
	 * @param mixed $p_mValue
     * @return void
	 */
	protected function set($p_mKeys, $p_mValue = null) {
		if(is_array($p_mKeys)) {
			foreach($p_mKeys as $key => $val) {
				$this->_view->set($key, $val);
			}
		} else {
			$this->_view->set($p_mKeys, $p_mValue);
		}
	}

	/**
	 * Load a view, but override the renderer to smarty
	 *
	 * @param string $p_tplFile The view filename. File extensions are optional.
	 * @param array $p_tplParams Optional parameters to the view file.
	 * @return void
	 *
	 */
	protected function loadSmarty($p_tplFile, $p_tplParams = array()) {
		$this->_view->loadsmarty($p_tplFile, $p_tplParams);
	}

    /**
     * Append to the list of stylesheets to be included
     *
     * @param mixed $p_mStylesheet This can be an existing array of stylesheets or a string.
     * @return void
     */
    protected function addStylesheet($p_mStylesheet) {
        $this->_view->addStylesheet(func_get_args());
    }

    /**
     * Append to the list of javascript files to be included
     *
     * @param mixed $p_mJavascript
     * @return void
     */
    protected function addJavascript($p_mJavascript) {
        $this->_view->addJavascript(func_get_args());
    }

	/**
	 * Override the default template file, with optional include for the .php or .tpl extension
     *
	 * @param string $p_sNewTemplateFile New Template Filename
	 * @todo have this lookup the template engines default extension and remove the smarty param
     * @return void
	 */
	protected function setTemplateFile($p_sNewTemplateFile, $p_bUseSmarty = false) {
		$this->_view->setTemplateFile($p_sNewTemplateFile, $p_bUseSmarty);
	}


	/**
	 * Setter for setting the flash message to appear on next page load.
     *
	 * @return void
	 */
	protected function setFlashMessage($p_sMessage, $p_bSuccess = true) {
		PPI_Input::setFlashMessage($p_sMessage, $p_bSuccess);
	}

	/**
	 * Getter for the flash message.
     *
	 * @return string
	 */
	protected function getFlashMessage() {
		return PPI_Input::getFlashMessage();
	}

	/**
	 * Clear the flash message from the session
     *
	 * @return void
	 */
	protected function clearFlashMessage() {
		PPI_Input::clearFlashMessage();
	}

    /**
     * Get the full current URI
     *
     * @todo Maybe just strip off baseUrl from the URL and that's our URI
     * @return string
     */
	protected function getCurrUrl() {
		return PPI_Helper::getCurrUrl();
	}

	/**
	 * Get the full URL
	 *
	 * @return string
	 */
	protected function getFullUrl() {
		return PPI_Helper::getFullUrl();
	}

	/**
	 * Get the base url set in the config
     *
	 * @return string
	 */
	protected function getBaseUrl() {
		return $this->getConfig()->system->base_url;
	}

	/**
	 * PPI_Controller::getConfig()
	 * Returns the PPI_Config object
	 * @return object
	 */
	protected function getConfig() {
		return PPI_Helper::getConfig();
	}

	/**
	 * Returns the session object
     *
	 * @return object PPI_Model_Session
	 */
	protected function getSession($p_mOptions = null) {
		return PPI_Helper::getSession($p_mOptions);
	}

	/**
	 * Returns the session object
     *
	 * @return object PPI_Model_Session
	 */
	protected function getRegistry() {
		return PPI_Helper::getRegistry();
	}

	/**
	 * Get the cache object from PPI_Helper
	 *
	 * @return object
	 */
	protected function getCache($p_mOptions = null) {
		return PPI_Helper::getCache($p_mOptions);
	}

	/**
	 * Checks if the current user is logged in
     *
	 * @return boolean
	 */
	protected function isLoggedIn() {
		$aAuthData = $this->getAuthData();
		return !empty($aAuthData);
	}

	/**
	 * Gets the current logged in users authentication data
     *
     * @param boolean $p_bUseArray Default is true. If false then will return an object instead
	 * @return array|object
	 */
	protected function getAuthData($p_bUseArray = true) {
		$authData = $this->getSession()->getAuthData();
		return $p_bUseArray ? $authData : (object) $authData;
	}

	/**
	 * Get a parameter from the URI
	 *
	 * @param string $p_sVar The key name
	 * @param mixed $p_mDefault The default value returned if the key doesn't exist
	 * @return mixed
	 */
	protected function get($p_sVar, $p_mDefault = null) {
		return $this->_input->get($p_sVar, $p_mDefault);
	}

	/**
	 * Access the HTTP POST variables
	 *
	 * @param string $p_sVar The variable name to access
	 * @param mixed $p_mDefault The default value if the key defined doesn't exist
	 * @return mixed
	 */
	protected function post($p_sVar = null, $p_mDefault = null) {
		return $this->_input->post($p_sVar, $p_mDefault);
	}

	/**
	 * Does a particular post variable exist
	 *
	 * @param string $p_sKey The post variable
	 * @return boolean
	 */
	protected function hasPost($p_sKey) {
		return $this->_input->hasPost($p_sKey);
	}

	/**
	 * Has the form been submitted ?
	 *
	 * @return boolean
	 */
	protected function isPost() {
		return $this->_input->isPost();
	}

	/**
	 * Obtain strippost from the input object
	 * Will give all HTTP POST variables that match a specific prefix
	 *
	 * @param unknown_type $p_sPrefix
	 * @return unknown
	 */
	protected function stripPost($p_sPrefix) {
		return $this->_input->stripPost($p_sPrefix);
	}

	/**
	 * Remove a value from HTTP POST
	 *
	 * @param string $p_sKey
	 * @return void
	 */
	protected function removePost($p_sKey) {
		$this->_input->removePost($p_sKey);
	}

	/**
	 * Empty the entire HTTP POST
	 *
	 * @return void
	 */
	protected function emptyPost() {
		$this->_input->emptyPost();
	}

    /**
     * Get a plugin by name.
     *
     * @param string $p_sPluginName
     * @return
     */
	protected function getPlugin($p_sPluginName) {
		return PPI_Helper::getPlugin()->getPlugin($p_sPluginName);
	}

}
