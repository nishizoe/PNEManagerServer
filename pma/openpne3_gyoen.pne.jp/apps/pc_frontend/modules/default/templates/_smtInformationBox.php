<div class="row">
<div class="gadget_header span12"><?php echo __('Information') ?></div>
</div>
<?php use_helper('Javascript') ?>
<?php
op_include_parts('informationBox', 'information_'.$gadget->getId(), array('body' => $gadget->getRawValue()->getConfig('value')))
?>

