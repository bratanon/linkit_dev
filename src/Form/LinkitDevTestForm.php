<?php

/**
 * @file
 * Contains \Drupal\linkit_dev\Form\LinkitTestForm.
 */

namespace Drupal\linkit_dev\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\simpletest\Form\SimpletestTestForm;

/**
 * Quicker way to handle just the Linkit tests.
 */
class LinkitDevTestForm extends SimpletestTestForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'linkit_dev_test_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Run tests'),
      '#tableselect' => TRUE,
      '#button_type' => 'primary',
    );
    $form['clean'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Clean test environment'),
      '#weight' => 200,
    );
    $form['clean']['op'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Clean environment'),
      '#submit' => array('simpletest_clean_environment'),
    );

    $form['tests'] = array(
      '#type' => 'table',
      '#id' => 'linkit-dev-form-table',
      '#tableselect' => TRUE,
      '#header' => array(
        $this->t('Test'),
       $this->t('Description'),
      ),
      '#empty' => $this->t('No tests to display.'),
    );

    // Generate the list of tests arranged by group.
    $groups = linkit_dev_get_all();
    foreach ($groups as $group => $tests) {
      // Cycle through each test within the current group.
      foreach ($tests as $class => $info) {
        $form['tests'][$class]['title'] = array(
          '#type' => 'label',
          '#title' => '\\' . $info['name'],
        );
        $form['tests'][$class]['description'] = array(
          '#prefix' => '<div class="description">',
          '#plain_text' => $info['description'],
          '#suffix' => '</div>',
        );
      }
    }

    return $form;
  }

}
