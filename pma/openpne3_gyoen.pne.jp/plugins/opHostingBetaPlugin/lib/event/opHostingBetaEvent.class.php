<?php


class opHostingBetaEvent
{
  
  /**
   *
   * @var opHostingBetaLimit
   */
  private static $_limitModel;

  /**
   * @todo エラー処理をちゃんとする
   */
  public static function checkUserLimit()
  {
    self::$_limitModel = new opHostingBetaLimit();

    if (self::$_limitModel->isOverUserLimit())
    {
      throw new RuntimeException("ユーザー数の上限に達しています。 有料サービスに切り替えてください");
    }

  }

}
