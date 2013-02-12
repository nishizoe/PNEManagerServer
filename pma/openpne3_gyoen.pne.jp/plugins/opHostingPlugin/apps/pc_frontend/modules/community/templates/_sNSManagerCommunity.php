<form action="" method="POST">
  <table>
    <?php echo $form['name']->renderRow() ?>

    <?php if(opHostingUtil::canUseThemePlugin()): ?>
      <?php include_partial('themeSelectRows', array('form' => $form)); ?>
    <?php endif; ?>

    <tr>
      <td colspan="2">
        <?php echo $form->renderHiddenFields(); ?>
        <input type="submit" />
      </td>
    </tr>
  </table>
</form>