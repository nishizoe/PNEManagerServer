<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot(); ?>

<h2><?php echo __('Register new account') ?></h2>

<form action="<?php url_for('admin/addUser') ?>" method="post">
<table>
<?php if (!opHostingBetaUtil::canUserAdd()): ?>
<tr><td colspan="2"><ul class="error_list" id="limit_error"><li>ユーザーが上限に達しているためユーザーを増やせません</li></ul></td></tr>
<?php endif; ?>

<?php echo $form ?>

<?php if (opHostingBetaUtil::canUserAdd()): ?>
<td colspan="2"><input type="submit" id="addUserButton" value="<?php echo __('Add') ?>" /></td>
<?php else: ?>
<td colspan="2"><input type="submit" id="addUserButton" value="<?php echo __('Add') ?>" disabled="disabled" /></td>
<?php endif; ?>
</tr>
</table>
</form>
