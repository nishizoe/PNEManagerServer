<?php
  op_include_parts('listBox', 'communityHome', $sf_data->getRaw('listBoxOption'));
?>

<ul>
<?php if ($isEditCommunity): ?>
<li><?php echo link_to(__('Edit this %community%'), '@community_edit?id=' . $community->getId()) ?></li>
<?php endif; ?>

<?php if (!$isAdmin): ?>
<?php if ($isCommunityMember): ?>
<li><?php echo link_to(__('Leave this %community%'), '@community_quit?id=' . $community->getId()) ?></li>
<?php else : ?>
<li><?php echo link_to(__('Join this %community%'), '@community_join?id=' . $community->getId()) ?></li>
<?php endif; ?>
<?php endif; ?>
</ul>
