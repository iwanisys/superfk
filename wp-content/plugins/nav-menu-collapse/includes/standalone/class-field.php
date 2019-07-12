<?php
/**
 * Meta box field functionality.
 * 
 * @since 1.0.0
 * 
 * @package    Nav Menu Collapse
 * @subpackage Field
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('Nav_Menu_Collapse_Field'))
{
	/**
	 * Class used to implement the field object.
	 *
	 * @since 1.1.2 Various functionality improvements.
	 * @since 1.0.0
	 * 
	 * @uses Nav_Menu_Collapse_Wrapper
	 */
	class Nav_Menu_Collapse_Field extends Nav_Menu_Collapse_Wrapper
	{
		/**
		 * Constructor function.
		 * 
		 * @since 1.1.2 Added default values and updated call to set properties.
		 * @since 1.1.0 Removed base object argument and added help tab output.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  array $options Optional options for the field.
		 * @return void
		 */
		public function __construct($options = array())
		{
			parent::__construct();

			$defaults = array
			(
				/* @var array CSS classes added to the field wrapper. */
				'classes' => array(),

				/* @var array Conditions for a field to be visible. */
				'conditional' => array(),

				/* @var string Content added to the field. */
				'content' => '',

				/* @var string Short description display with the field. */
				'description' => '',

				/* @var string Long description displayed in the help tab. */
				'help_description' => '',

				/* @var array CSS classes added to the field input element. */
				'input_classes' => array(),

				/* @var string Output label displayed with the field. */
				'label' => '',

				/* @var integer Max length for the field. Only works with Text fields. */
				'max_length' => -1,

				/* @var string Base name for the field. */
				'name' => '',

				/* @var string Meta box option name. */
				'option_name' => '',

				/* @var string Type of field to output. */
				'type' => 'text',

				/* @var string Current value for the field. */
				'value' => ''
			);

			$this->_set_properties($defaults, $options);

			if (!empty($this->help_description))
			{
				Nav_Menu_Collapse_Help::add_block($this->label, $this->help_description);
			}
		}

		/**
		 * Get a default option based on the provided name.
		 * 
		 * @since 1.2.1 Escaped ID value.
		 * @since 1.1.2 Improved default call to only include generated values.
		 * @since 1.0.0
		 * 
		 * @access protected
		 * @param  string $name Name of the option to return.
		 * @return string       Default option if it exists, otherwise an empty string.
		 */
		protected function _default($name)
		{
			switch ($name)
			{
				/* @var string Generated DOM ID. */
				case 'id':

					return esc_attr($this->generate_id());

				/* @var string Generated field identifier attributes. */
				case 'identifiers':

					return (empty($this->id)) ? '' : ' id="' . $this->id . '" name="' . $this->id . '"';

				/* @var string Generated label attributes. */
				case 'label_attr':

					return (empty($this->id)) ? '' : ' for="' . $this->id . '"';
			}

			return parent::_default($name);
		}

		/**
		 * Generate the output for the field.
		 * 
		 * @since 1.1.2 Cleaned up function and separated some functionality.
		 * @since 1.0.0
		 * 
		 * @access public
		 * @param  boolean     $echo True if the field should be echoed.
		 * @return string/void       Generated field if $echo is false.
		 */
		public function output($echo = false)
		{
			$this->_push('classes', 'nmc-field');
			$this->_push('classes', 'nmc-field-' . str_replace('_', '-', $this->type));

			$output = $this->simple_output();

			if (!empty($output) && in_array('nmc-hidden', $this->classes))
			{
				$output .= '<div class="nmc-hidden nmc-field-spacer"></div>';
			}

			if (!$echo)
			{
				return $output;
			}

			echo $output;
		}

		/**
		 * Simple field output.
		 * 
		 * @since 1.1.2
		 * 
		 * @access private
		 * @return string Generated simple field.
		 */
		private function simple_output()
		{
			$output = '';
			$field = (method_exists($this, 'field_' . $this->type)) ? call_user_func(array($this, 'field_' . $this->type)) : $this->field_text();
			
			if (!empty($field))
			{
				$field .= $this->generate_condition_fields();
				$label_description = '';
				$description = $this->generate_description();
				
				if ($this->is_tall)
				{
					$label_description = $description;
					$description = '';
				}
				
				$label = (empty($this->label)) ? '' : '<div class="nmc-field-label">' .
					$this->generate_label() .
					$label_description .
					'</div>';

				$output = $this->wrap_field($label .
					'<div class="nmc-field-input">' .
					$field .
					$description .
					'</div>');
			}

			return $output;
		}

		/**
		 * Generate a checkbox field.
		 * 
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return string Generated checkbox field.
		 */
		private function field_checkbox()
		{
			return ($this->id == '') ? '' : '<input' . $this->identifiers . ' type="checkbox" value="1" ' . $this->get_input_classes() . ' ' . checked('1', $this->value, false) . ' />';
		}

		/**
		 * Generate an HTML field.
		 * 
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return string Generated HMTL field.
		 */
		private function field_html()
		{
			return '<div class="nmc-html' . $this->get_input_classes(false) . '">' .
				wpautop(do_shortcode($this->content)) .
				'</div>';
		}

		/**
		 * Generate a submit button.
		 * 
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return string Generated submit button.
		 */
		private function field_submit()
		{
			$this->content = ($this->content == '') ? __('Submit', 'nav-menu-collapse') : $this->content;

			return '<button' . $this->identifiers . ' type="submit" disabled="disabled" class="button button-large button-primary nmc-button' . $this->get_input_classes(false) . '"><span>' . $this->content . '</span></button>';
		}

		/**
		 * Generate a text field.
		 * 
		 * @since 1.3.0 Removed max length.
		 * @since 1.0.0
		 * 
		 * @access private
		 * @return string Generated text field.
		 */
		private function field_text()
		{
			if (empty($this->id)) return '';

			return '<input' . $this->identifiers . ' type="text" value="' . esc_attr($this->value) . '"' . $this->get_input_classes() . ' />';
		}

		/**
		 * Generate contition fields.
		 * 
		 * @since 1.2.1 Optimized conditional ID.
		 * @since 1.1.2
		 * 
		 * @access private
		 * @return string Generated condition fields.
		 */
		private function generate_condition_fields()
		{
			$output = '';

			if (is_array($this->conditional) && !empty($this->conditional))
			{
				foreach ($this->conditional as $condition)
				{
					if (is_array($condition) && isset($condition['field']) && isset($condition['value']))
					{
						if (!isset($condition['compare']))
						{
							$condition['compare'] = '=';
						}

						$output .= '<div class="nmc-hidden nmc-condition" ' .
							'data-nmc-conditional="' . $this->id . '" ' .
							'data-nmc-field="' . esc_attr($this->generate_id($condition['field'])) . '" ' .
							'data-nmc-value="' . esc_attr($condition['value']) . '" ' .
							'data-nmc-compare="' . esc_attr($condition['compare']) . '">' .
							'</div>';
					}
				}
			}

			return $output;
		}
		
		/**
		 * Generate the field description.
		 * 
		 * @since 1.3.0
		 * 
		 * @access private
		 * @return string Wrapped field description.
		 */
		private function generate_description()
		{
			if (empty($this->description)) return '';
			
			return '<div class="nmc-description">' .
				'<label' . $this->label_attr . '>' . $this->description . '</label>' .
				'</div>';
		}

		/**
		 * Generate a field ID.
		 *  
		 * @since 1.1.2 Renamed function.
		 * @since 1.0.0
		 * 
		 * @access private
		 * @param  array  name The base name for the field. If excluded the default field name will be used.
		 * @return string      Generated field ID.
		 */
		private function generate_id($name = '')
		{
			$name = (empty($name)) ? $this->name : $name;

			return (empty($name) || empty($this->option_name)) ? $name : $this->option_name . '[' . $name . ']';
		}
		
		/**
		 * Generate the field label.
		 * 
		 * @since 1.3.0
		 * 
		 * @access private
		 * @return string Wrapped field label.
		 */
		private function generate_label()
		{
			if (empty($this->label)) return '';
			
			return '<label' . $this->label_attr . '><strong>' . $this->label . '</strong></label>';
		}

		/**
		 * Get the input class(es).
		 * 
		 * @since 1.3.0 Changed 'check_array' call.
		 * @since 1.1.2 Renamed function.
		 * @since 1.0.0
		 * 
		 * @access private
		 * @param  boolean $add_attr True if the class attribute should be added.
		 * @return string            Generated field class(es).
		 */
		private function get_input_classes($add_attr = true)
		{
			if (!empty($this->input_classes))
			{
				$classes = Nav_Menu_Collapse_Utilities::check_array($this->input_classes);
				$classes = esc_attr(implode(' ', $classes));

				return ($add_attr) ? ' class="' . $classes . '"' : ' ' . $classes;
			}

			return '';
		}
		
		/**
		 * Add a general wrap around the field.
		 * 
		 * @since 1.3.0
		 * 
		 * @access private
		 * @param  string $field Field HTML to wrap.
		 * @return string        Wrapped field.
		 */
		private function wrap_field($field)
		{
			if (empty($field)) return '';
			
			return '<div class="' . esc_attr(implode(' ', $this->classes)) . '">' .
				$field .
				'</div>';
		}
	}
}
