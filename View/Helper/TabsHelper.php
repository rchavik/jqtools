<?php

App::import('Helper', 'Jqtools.Jqtools');
class TabsHelper extends JqtoolsHelper {

	var $script = 'tabs/tabs';

	var $tab = false;
	var $tabs = array();

	function beforeRender() {
		parent::beforeRender();
		$params = $this->_View->params;
		if (isset($params['isAjax']) && $params['isAjax'] === true) {
			return;
		}
		if (isset($params['admin']) && $params['admin'] === true) {
			return;
		}
		$this->Html->css('/jqtools/css/tabs', null, array('inline' => false));
	}

	function create($name) {
		$this->tab = $name;
		$this->tabs[$name] = array();
	}

	function __currentTab() {
		if (empty($this->tab)) {
			$this->tab = 'tabs-' . rand(1, 100);
		}
		return $this->tab;
	}

	function add($pane, $element, $options = array()) {
		$name = $this->__currentTab();
		$this->tabs[$name][] = compact('pane', 'element', 'options');
	}

	function tabs() {
		$name = $this->__currentTab();
		if (!$name || ! isset($this->tabs[$name])) return;

		$out = $tabs = $panes = '';
		foreach ($this->tabs[$name] as $tab) {
			extract($tab);
			$a = $this->Html->link($tab['pane'], '#' . $pane);
			$tabs .= $this->Html->tag('li', $a);

			if (is_array($element) && isset($element['html'])) {
				$panes .= $this->Html->div('pane', $element['html'], $options);
			} else {
				$panes .= $this->Html->div('pane', $this->View->element($tab['element'], $tab['options']));
			}
		}

		$domId = Inflector::slug(strtolower($name), '-');
		$ulOptions = array(
			'id' => $domId,
			'class' => 'tabs',
			'escape' => false
			);

		$out .= $this->Html->tag('ul', $tabs, $ulOptions);
		$out .= $this->Html->tag('div', $panes, array('class' => 'panes'));

		$script =<<<EOF
jQuery('#{$domId}').tabs('div.panes > div');
EOF;
		$this->Js->buffer($script);
		return $out;
	}
}
