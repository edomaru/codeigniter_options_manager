<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Options
 * Provide easy way to store application configuration in database
 * with easy to use interface
 *
 * @author Eding Muhamad Saprudin <masaruedogawa@gmail.com>
 */
class Options {

	private $container_tag_open      = '<div>';
	private $container_tag_close     = '</div>';
	private $content_tag_open        = '<div style="padding:0 20px;">';
	private $content_tag_close       = '</div>';
	private $tab_tag_open            = '<ul class="nav nav-tabs" role="tablist">';
	private $tab_tag_close           = '</ul>';
	private $tab_item_tag            = '<li role="presentation"><a href="#%1$s" aria-controls="%1$s" role="tab" data-toggle="tab">%2$s</a></li>';
	private $tab_content_tag_open    = '<div class="tab-content" style="margin-top: 20px;">';
	private $tab_content_tag_close   = '</div>';
	private $tab_panel_tag_open      = '<div role="tabpanel" class="tab-pane" id="%s">';
	private $tab_panel_tag_close     = '</div>';
	private $header_option_tag_open  = '<fieldset><legend>%s</legend>';
	private $header_option_tag_close = '</fieldset>';
	private $help_block_tag_open     = '<span class="help-block">';
	private $help_block_tag_close    = '</span>';
	private $submit_button_tag       = '<button type="submit" name="submit" class="btn btn-primary">Update</button>';
	private $item_tag_open           = '<div class="form-group">';
	private $item_tag_close          = '</div>';
	private $element_tag_open        = '<div class="col-md-9">';
	private $element_tag_close       = '</div>';
	private $item_class              = 'form-control';
	private $form_class              = 'form-horizontal';
	private $label_class             = 'col-md-3 control-label';
	private $item_active             = '';
	private $item_active_class       = 'active';
	private $show_hidden_option      = false;
	private $options                 = array();

	public function __construct($config = array())
	{
		$this->_ci = get_instance();
		$this->_db = $this->_ci->load->database('default', true);
		$this->_ci->load->helper(array('form', 'options', 'options_custom'));
		
		$this->initialize($config);
		$this->options = $this->construct_options();
	}

	/**
	 * Initialize options library
	 * 
	 * @param  array  $config html configuration tags list
	 * @return void         
	 */
	public function initialize($config = array())
	{
		if (count($config)) 
		{
			foreach ($config as $key => $value) {
				$this->$key = $value;
			}
		}			
	}

	private function construct_options($group = null)
	{		
		$options = array();

		if (is_null($group)) {
			$this->_db->where("group is null");	
		}
		else {
			$this->_db->where("group", $group);
		}

		$groups = $this->_db->get("options");

		if ($groups->num_rows()) 
		{
			foreach ($groups->result() as $row) 
			{
				$option_name  = $row->option_name;
				$option_value = $row->option_value;

				if ( is_serialized($option_value) ) {
					$option_value = unserialize($option_value);					
				}
				else 
				{
					$option_value = array(
						'value'   => $option_value,
						'label'   => ucwords( str_replace("_", " ", $option_name) ),
						'visible' => 1
					);
				}
				
				$options[$row->option_name] = $option_value;
				$options[$row->option_name]['options'] = $this->construct_options($option_name);				
			}	
		}

		return $options;
	}


	/**
	 * Save setting changes to database
	 *
	 * @return bool 
	 */
	public function save_settings()
	{
		$this->_db = $this->_ci->load->database("default", true);

		$affected_rows = 0;

		// sanitize
		$data = array();
		foreach ($_POST as $group => $value) {
			$data[$group] = $this->_ci->input->post($group);
		}	

		// save to database
		foreach ($data as $group => $values) 
		{			
			if ( ! empty($values) )
			{
				foreach ($values as $key => $options) 
				{
					foreach ($options as $name => $value) 
					{
						$option_value = $value;
						$group_name   = is_numeric($key) ? $group : $key;

						// cek nilai sebelumnya
						// jika berupa serialize, update bagian value saja
						$this->_db->where("option_name", $name);
						$this->_db->where("group", $group_name);
						$row = $this->_db->get("options")->row();
						// print_r($row);

						if ( is_serialized($row->option_value) ) 
						{
							$option_value = unserialize($row->option_value);		
							$option_value['value'] = $value;
							$option_value = serialize($option_value);
						}	


						// set the value
						$this->_db->set("option_value", $option_value);
						$this->_db->where("option_name", $name);
						$this->_db->where("group", $group_name);			
						$this->_db->update("options");

						if ($this->_db->affected_rows()) {
							$affected_rows++;
						}
					}
				}	
			}
		}

		return $affected_rows > 0;
	}

	public function render($action = "", $form_attr = array())
	{
		$form_attr['method'] = "post";
		$form_attr['class']  = $this->form_class;
		
		$html  = $this->container_tag_open;

		$html .= form_open("", $form_attr);
		$html .= $this->render_content(true);		
		$html .= form_close();
		
		$html .= $this->container_tag_close;

		return $html;
	}

	public function render_content($render_button = false)
	{
		$html = $this->content_tag_open;

		// jika opsi grup lebih dari satu, tampilkan dalam bentuk tab
		// sebaliknya, tampilkan dalam bentuk form pada umumnya
		if (count($this->options) > 1) 
		{			
			/**
			 * Render the tab navigations
			 */
			$html .= $this->tab_tag_open;

			$tab_index = 0;

			foreach ($this->options as $group => $values) 
			{
				// jika visible non-aktif, skip that
				if (  $this->show_hidden_option == false && isset($values['visible']) && $values['visible'] == 0) continue;

				// set the first group as active tab
				if ($tab_index == 0) 
				{
					$this->item_active = $group;
					$tab_index++;
				}

				$tab   = sprintf($this->tab_item_tag, $group, $values['label']);
				$html .= $this->set_active($tab, $group);
			}

			$html .= $this->tab_tag_close;						


			/**
			 * Render tab content
			 */
			
			$html .= $this->tab_content_tag_open;
			
			// loop the top level group option (level 1)
			foreach ($this->options as $group => $values) 
			{						
				// jika visible non-aktif, skip that
				if ( $this->show_hidden_option == false && isset($values['visible']) && $values['visible'] == 0) continue;				
				
				// open the tab panel				
				$tab_panel = sprintf($this->tab_panel_tag_open, $group);				
				$tab_panel = str_replace("</div>", "", $this->set_active($tab_panel, $group) ); // weired bro
				$html     .= $tab_panel;

				// loop through sub group (level 2)
				foreach ($values['options'] as $key => $options) 
				{					
					if ( ! empty($options['options']) )
					{						
						// open the header option tag
						$header_option = sprintf($this->header_option_tag_open, $options['label']);
						$html .= $header_option;

						// loop every option item (level 3)
						foreach ($options['options'] as $name => $value) 
						{
							if ( $this->show_hidden_option == false && isset($value['visible']) && $value['visible'] == 0) continue;				

							$html .= $this->render_item($value, $name, $group, $key);		
						}

						// close the header option tag
						$html .= $this->header_option_tag_close;
					}
					else
					{						
						$html .= $this->render_item($options, $key, $group);
					}
				}

				// close the tab panel
				$html .= $this->tab_panel_tag_close;
			}			

			$html .= $this->tab_content_tag_close;
		}
		else
		{

		}

		if ($render_button) 
		{
			$html .= '<hr>';
			$html .= $this->submit_button_tag;
		}
		
		return $html .= $this->content_tag_close;	
	}

	private function render_item($row, $key, $group, $sub_group = 0)
	{		
		// tag open of the item
	    $html = $this->item_tag_open;
	    	    

	    // get the form element with label
	    $html .= $this->get_form_element($row, $key, $group, $sub_group);
	    
	    // render the help block if any
	    if ( ! empty($row['description']) ) 
	    {
	    	$html .= $this->help_block_tag_open;
	    	$html .= $row['description'];
	    	$html .= $this->help_block_tag_close;
	    }

		// tag close of the item
	    $html .= $this->item_tag_close;

	    return $html;
	}

	public function get_form_element($values, $key, $group, $sub_group = 0)
	{		
		// render the label
		$name          = "$group" . "[$sub_group]" . "[$key]";
		$id            = "{$group}_{$sub_group}_{$key}";
		$label_class[] = $this->label_class;
		
		if (isset($values['label_class'])) {
			$label_class[] = $values['label_class'];
		}
		
		$label_class = implode(" ", $label_class);
		$item        = form_label($values['label'], $id, array('class' => $label_class)) . "\n";
		
		// render form element
		$item .= $this->element_tag_open;

		// render the form element
		if (empty($values['field']) || $values['field'] == 'input') 
		{
			$item .= form_input($name, $values['value'], "class='{$this->item_class}' id='{$id}'");
		}
		elseif ($values['field'] == 'password') {
			$item .= form_textarea($name, $values['value'], "class='{$this->item_class}' id='{$id}'");
		}
		elseif ($values['field'] == 'textarea') {
			$item .= form_textarea($name, $values['value'], "class='{$this->item_class}' id='{$id}' row='3'");
		}
		elseif ($values['field'] == 'dropdown') {			
			$options = empty($values['dropdown_function']) ? $values['dropdown_options'] : call_user_func_array($values['dropdown_function'], isset($values['dropdown_params']) ? ( is_array($values['dropdown_params']) ? $values['dropdown_params'] : array($values['dropdown_params']) ) : array() );
			$item .= form_dropdown($name, $options, $values['value'], "class='{$this->item_class}' id='{$id}'");
		}

		return $item . $this->element_tag_close;
	}

	/**
	 * Set active item
	 * 
	 * @param string $html html tag that would be injected
	 * @param string $group html tag that has injected with active class
	 */
	private function set_active($html, $group)
	{
		if ($this->item_active != '' && $group == $this->item_active) 
		{
			$doc = new DOMDocument();
			$doc->loadHTML($html);
			foreach($doc->getElementsByTagName('*') as $tag ){
				$tag->setAttribute('class', ($tag->hasAttribute('class') ? $tag->getAttribute('class') . ' ' : '') . $this->item_active_class);
			}

			return preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML() );
		}

		return $html;
	}

}