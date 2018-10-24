<?php
namespace Drupal\webform_handler_user_creation\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\webform\Entity\WebformSubmission;
/**
 * Webform submission action handler.
 *
 * @WebformHandler(
 *   id = "usercreate",
 *   label = @Translation("User Create"),
 *   category = @Translation("Action"),
 *   description = @Translation("Creates a user after submission"),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_UNLIMITED,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */

class UserCreationHandler extends WebformHandlerBase {
  /**
   * {@inheritdoc}
   */
  public function postSave(WebformSubmissionInterface $webform_submission, $update = TRUE) {
		$user = \Drupal\user\Entity\User::create();
		$language = \Drupal::languageManager()->getCurrentLanguage()->getId();
		$user_name = $webform_submission->getElementData('user_name');
		// Mandatory user creation settings
		$user->enforceIsNew();
		$user->setPassword('testPassword');
		$user->setEmail('test@example.com');
		$user->setUsername($user_name); // This username must be unique and accept only a-Z,0-9, - _ @ .
		$user->set("langcode", $language);
		// Optional settings
		$user->set("init", 'email');
		$user->set("preferred_langcode", $language);
		$user->set("preferred_admin_langcode", $language);
		$user->activate();
		// Add a custom role
		$user->addRole('CustomRoleName');
		//Save user
		$user->save();
		
  }
}

