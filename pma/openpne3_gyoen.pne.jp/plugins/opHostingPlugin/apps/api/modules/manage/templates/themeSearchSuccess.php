<?php

$result = array();

$usedTheme = Doctrine::getTable('SnsConfig')->retrieveByName('Theme_used')->getValue();
if (0 < count($entrys))
{
  foreach ($entrys as $entry)
  {
    if ($usedTheme == $entry)
    {
      $result[] = array('dir' => $entry, 'used' => true);
    }
    else
    {
      $result[] = array('dir' => $entry, 'used' => false);
    }
  }
}
else
{
  return array(
    'status' => 'error',
    'message' => 'nothing directory',
  );
}

return array(
  'status' => 'success',
  'data' => $result,
);
