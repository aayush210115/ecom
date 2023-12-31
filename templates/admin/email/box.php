<?php
/**
 * @var $email \Jigoshop\Entity\Email Currently displayed email.
 * @var $emails array List of registered emails.
 */
?>
<div class="jigoshop" data-id="<?= $email->getId(); ?>">
	<?= \Jigoshop\Admin\Helper\Forms::text([
		'name' => 'jigoshop_email[subject]',
		'label' => __('Subject', 'jigoshop'),
		'value' => $email->getSubject(),
    ]); ?>
	<?= \Jigoshop\Admin\Helper\Forms::select([
		'id' => 'jigoshop_email_actions',
		'name' => 'jigoshop_email[actions]',
		'label' => __('Actions', 'jigoshop'),
		'multiple' => true,
		'placeholder' => __('Select action...', 'jigoshop'),
		'options' => $emails,
		'value' => $email->getActions(),
    ]); ?>
</div>
<div class="clear"></div>
