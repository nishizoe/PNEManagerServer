<?php if ('1' === $id): ?>
<div class="partsHeading"><h3>簡単セットアップ</h3></div>
<div class="easy-setup-wrapper">
  <div>
    <label style="display: inline;" for="sns-name">SNS名</label>
    <input style="margin: 0;" type="text" id="sns-name" value="<?php echo $op_config['sns_name'] ?>">
    <button id="sns-name-submit" class="btn btn-mini">変更</button>
  </div>

  <div id="theme-list">
    <h3>テーマ選択</h3>
  </div>
</div>

<script id="themeListTemplate" type="text/x-jquery-tmpl">
<div>
  {{if used == true }}
  <input type="radio" name="theme" value="${dir}" class="theme-select" checked> ${dir}
  {{else}}
  <input type="radio" name="theme" value="${dir}" class="theme-select"> ${dir}
  {{/if}}
</div>
</script>
<?php endif; ?>
