<?php

namespace Drupal\select2_widget\Element;

use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\Select;
use Drupal\Core\Render\Element\Textfield;

/**
 * Provides an select2 entity autocomplete form element.
 *
 * The #default_value accepted by this element is either an entity object or an
 * array of entity objects.
 *
 * @FormElement("select2_entity_autocomplete")
 */
class Select2EntityAutocomplete extends Select {

  protected $entityAutocomplete;

  public function getInfo() {
    $info = parent::getInfo();
    $class = get_class($this);

  // Apply default form element properties.
    $info['#target_type'] = NULL;
    $info['#selection_handler'] = 'default';
    $info['#selection_settings'] = array();
    $info['#tags'] = TRUE;
    $info['#autocreate'] = NULL;
    // This should only be set to FALSE if proper validation by the selection
    // handler is performed at another level on the extracted form values.
    $info['#validate_reference'] = TRUE;
    array_unshift($info['#process'], array(Textfield::class, 'processAutocomplete'));

    // IMPORTANT! This should only be set to FALSE if the #default_value
    // property is processed at another level (e.g. by a Field API widget) and
    // it's value is properly checked for access.
    $info['#process_default_value'] = TRUE;

    $info['#element_validate'] = array(array($class, 'validateEntityAutocomplete'));
    array_unshift($info['#process'], array($class, 'processEntityAutocomplete'));

    return $info;
  }

  public static function processEntityAutocomplete(array &$element, FormStateInterface $form_state, array &$complete_form) {
    $element = EntityAutocomplete::processEntityAutocomplete($element, $form_state, $complete_form);
    $element['#autocomplete_route_name'] = 'select2_widget.entity_autocomplete';
    return $element;
  }

  /**
   * Form element validation handler for entity_autocomplete elements.
   */
  public static function validateEntityAutocomplete(array &$element, FormStateInterface $form_state, array &$complete_form) {
    return EntityAutocomplete::validateEntityAutocomplete($element, $form_state, $complete_form);
  }

}