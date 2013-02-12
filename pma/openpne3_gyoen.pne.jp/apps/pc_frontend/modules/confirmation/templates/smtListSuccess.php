<?php if (count($list)): ?>
<div class="row">
  <div class="gadget_header span12"><?php echo __($config[$category]) ?></div>
</div>
<p><?php echo __('You have the following pending requests. Select "Accept" or "Reject".') ?></p>


<?php foreach ($list as $item): ?>
<?php echo $form->renderFormTag(url_for('@confirmation_decision?id='.$item['id'].'&category='.$category)) ?>
<?php echo $form->renderHiddenFields() ?>
<div class="ditem">
  <div class="item">
    <table style="background-color: #fff;width: 100%">
      <tr>
        <td style="width: 30%;" class="photo" rowspan="<?php echo count($item['list']) + 1 ?>">
          <?php echo link_to(op_image_tag_sf_image($item['image']['url'], array('size' => '76x76')), $item['image']['link']); ?>
        </td>
      </tr>
      <?php foreach ($item['list'] as $k => $v): ?>
      <tr>
        <th><?php echo __($k) ?></th>
          <td>
            <?php if (isset($v['link'])): ?>
              <?php echo link_to(nl2br($v['text']), $v['link']) ?>
            <?php else: ?>
              <?php echo nl2br($v['text']) ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      <tr class="operation">
        <td colspan="3" style="text-align: center">
          <span class="moreInfo">
            <input type="submit" name="accept" value="<?php echo __('Accept') ?>" class="input_submit" />
            <input type="submit" value="<?php echo __('Reject') ?>" class="input_submit" />
          </span>
        </td>
      </tr>
    </table>
  </div>
</div>
<?php endforeach; ?>

<?php else: ?>
<?php op_include_box('searchMemberResult', __('You don\'t have any pending requests'), array('title' => __($config[$category]))) ?>
<?php endif; ?>

