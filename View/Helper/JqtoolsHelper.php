<?php

class JqtoolsHelper extends AppHelper {

	var $script = false;

	var $View = null;

	var $helpers = array(
		'Html',
		'Js' => array(
			'Jquery',
			),
		);

	public function __construct(View $View, $options = array()) {
		$this->_View =& ClassRegistry::getObject('view');
		Configure::load('Jqtools.jqtools');
		return parent::__construct($View, $options);
	}

	public function beforeRender() {
		$params = $this->_View->params;
		if (isset($params['isAjax']) && $params['isAjax'] === true) {
			return;
		}
		if (isset($params['admin']) && $params['admin'] === true) {
			return;
		}
		extract(Configure::read('Jqtools'));
		if ($full) {
			$minified = true;
			$script = '/jqtools/js/build/' . $version . '/jquery.tools';
		} else {
			$script = '/jqtools/js/build/' . $version . '/' . $this->script;
		}
		if ($minified) {
			$script .= '.min';
		}

		$this->Html->script($script, array('inline' => false, 'once' => true));
	}

}
