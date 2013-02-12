<?php if(opToolkit::isSecurePage()): ?>
<!-- NCFORM TMPL -->
<div class="ncform hide toggle1">
  <div class="row">
    <div class="span11 white font14 toggle1_close">
      <div class="center">
      <?php echo __('Notification Center') ?>
      </div>
    </div>
    <div class="span1">
      <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close')) ?>
    </div>
  </div>
  <div id="pushList" class="hide">
  </div>
  <div id="pushLoading" class="center"><?php echo op_image_tag('ajax-loader.gif') ?></div>
</div>
<!-- NCFORM TMPL -->

<script id="pushListTemplate" type="text/x-jquery-tmpl">
    <div class="{{if unread==false}}isread {{/if}}{{if category=="message" || category=="other"}}nclink {{/if}}row push marginl0"  data-notify-id="${id}" data-location-url="${url}" data-member-id="${member_id_from}">
      <div class="span3 push_icon">
        <img style="margin-left: 5px;" src="${icon_url}" class="rad4" width="48" height="48">
      </div>
      <div class="span9 push_content">
        <div class="row">
          <div class="span9">
          {{if category=="link"}}
            {{if unread==false}}
              <?php echo __('%Friend% link request') ?>
            {{/if}}
            {{if unread==true}}
              <?php echo __('Do you accept %friend% link request?') ?>
            {{/if}}
          {{else}}
            {{html body}}
          {{/if}}
          </div>
        </div>
        {{if category=="link"}}
        <div class="row{{if unread==false}} hide{{/if}}">
            <button class="span2 btn btn-primary small friend-notify-button friend-accept"><?php echo __('Accept') ?></button>
            <button class="span2 btn small friend-notify-button friend-reject"><?php echo __('Reject') ?></button>
            <div class="center hide ncfriendloading"><?php echo op_image_tag('ajax-loader.gif') ?></div>
            <div class="center hide ncfriendresultmessage"></div>
        </div>
        {{/if}}
      </div>
    </div>
</script>
<script id="pushCountTemplate" type="text/x-jquery-tmpl">
  {{if message!==0}}
  <span class="nc_icon1 label label-important" id="nc_count1">${message}</span>
  {{/if}}
  {{if link!==0}}
  <span class="nc_icon2 label label-important" id="nc_count2">${link}</span>
  {{/if}}
  {{if other!==0}}
  <span class="nc_icon3 label label-important" id="nc_count3">${other}</span>
  {{/if}}
</script>

<?php $publicFlags = Doctrine::getTable('ActivityData')->getPublicFlags(); //コードを一箇所にするためにviewに定義している ?>

<!-- POSTFORM TMPL -->
<div class="postform hide toggle1">
  <div class="row">
    <div class="span11 white font14 toggle1_close">
      <div class="center">
      <?php echo __('Post form') ?>
      </div>
    </div>
    <div class="span1">
      <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close')) ?>
    </div>
  </div>
  <div class="row posttextarea">
    <textarea id="tosaka_postform_body" class="span12" rows="4" tabindex="1" placeholder="<?php echo __('What are you doing now?') ?>"></textarea>
  </div>
  <div class="row">
    <div id="timeline-submit-error" class="timeline-mode" style="display: none;"></div>
    <input id="timeline-submit-upload" type="file" name="timeline-submit-upload" enctype="multipart/form-data" class="timeline-mode" style="display: none;">
    <span id="timeline-upload-photo-button" class="btn timeline-mode" style="display: none;"><span class="icon-camera"></span></span>
    <?php if (!$community): ?>
    <select id="timeline-public-flag" class="timeline-mode" style="display: none;">
          <?php foreach ($publicFlags as $value => $text): ?>
            <option value="<?php echo $value ?>" style="display: none;" class="timeline-mode"><?php echo __($text) ?></option>
          <?php endforeach; ?>
    </select>
    <?php else: ?>
    <span class="timeline-community-name" style="display: none" class="timeline-mode">このコミュニティに</span>
    <input type="hidden" id="timeline-public-flag">
    <?php endif; ?>
    <?php if ($community): ?>
    <button id="tosaka_postform_submit" data-community-id="<?php echo $community->getId() ?>" class="span12 btn small btn-primary basic-mode"><?php echo __('%post_activity%') ?></button>
    <button id="timeline_postform_submit" data-community-id="<?php echo $community->getId() ?>" class="span4 btn small btn-primary timeline-mode" tabindex="2" disabled="disabled" style="display: none;"><?php echo __('%post_activity%') ?></button>
    <?php else: ?>
    <button id="tosaka_postform_submit" class="span12 btn small btn-primary basic-mode"><?php echo __('%post_activity%') ?></button>
    <button id="timeline_postform_submit" class="span4 btn small btn-primary timeline-mode" tabindex="2" disabled="disabled" style="display: none;"><?php echo __('%post_activity%') ?></button>
    <?php endif; ?>
    <div class="photo-info" style="display: none;">
      <span id="photo-remove"><span class="icon-remove icon-white"></span></span>
      <span id="photo-file-name"></span>
    </div>
    <div class="center hide" id="timelinePostLoading" class="timeline-mode" style="display: none;"><?php echo op_image_tag('ajax-loader.gif') ?></div>
  </div>
</div>
<!-- POSTFORM TMPL -->
<?php endif ?>

<!-- new tosaka template -->

<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <div class="nav-collapse toggle1">
        <div class="row">
          <div class="span10 white font14 toggle1_close">MENU</div>
          <div class="span1">
            <?php echo op_image_tag('UPARROW', array('class' => 'toggle1_close', 'style' => 'padding-left: 22px;')) ?>
          </div>
        </div>
        <?php include_component('default', 'smtMenu') ?>
      </div>
      <a class="btn btn-navbar brand" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <?php if (opToolkit::isSecurePage()): ?>
      <div id="notification_center" class="center">
        <?php echo op_image_tag('NOTIFY_CENTER.png', array('height' => '32', 'class' => 'ncbutton')) ?>
      </div>
      <div class="right"><?php echo op_image_tag('POST.png', array('height' => '32', 'class' =>'postbutton')) ?></div>
      <?php endif ?>
    </div>
  </div>
</div>

<!-- end tosaka template -->
