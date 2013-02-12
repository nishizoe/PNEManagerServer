<?php slot('op_sidemenu'); ?>
<?php

$options = array(
    'object' => $community,
);
op_include_parts('memberImageBox', 'communityImageBox', $options);
?>

<?php

$options = array(
    'title' => __('%community% Members', array('%community%' => $op_term['community']->titleize())),
    'list' => $members,
    'crownIds' => array($communityAdmin->getId()),
    'link_to' => '@member_profile?id=',
    'use_op_link_to_member' => true,
    'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $community->countCommunityMembers()), '@community_memberList?id='.$community->getId())),
);
if ($isAdmin || $isSubAdmin)
{
  $options['moreInfo'][] = link_to(__('Management member'), '@community_memberManage?id='.$community->getId());
}
op_include_parts('nineTable', 'frendList', $options);
?>
<?php end_slot(); ?>

<?php slot('op_top') ?>
<?php if ($isCommunityPreMember) : ?>
<?php op_include_parts('descriptionBox', 'informationAboutCommunity', array('body' => __('You are waiting for the participation approval by %community%\'s administrator.'))) ?>
<?php endif; ?>
<?php end_slot(); ?>


<?php

  if (opHostingUtil::isSNSManagerCommunityURL())
  {
    include_partial('sNSManagerCommunity', array('form' => $form));
  }
  else
  {
    $listBoxOption = opHostingHelper::create_community_info_data(array(
                'op_term' => $op_term,
                'community' => $community,
                'communityAdmin' => $communityAdmin,
                'communitySubAdmins' => $communitySubAdmins
            ));

    $partialData = array(
        'community' => $community,
        'isEditCommunity' => $isEditCommunity,
        'isAdmin' => $isAdmin,
        'isCommunityMember' => $isCommunityMember,
        'listBoxOption' => $listBoxOption
    );

    include_partial('communityInfo', $partialData);
  }
?>
