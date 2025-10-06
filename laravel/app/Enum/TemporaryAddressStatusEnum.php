<?php 

namespace App\Enum;

class TemporaryAddressStatusEnum
{
  const PROCESSING = 0;
  const APPROVED = 1;
  const REJECTED = 2;
  const CANCELED = 3;
  const REPLACED = 4;

  public static function getArray()
  {
    return [
      self::PROCESSING => 'Processing',
      self::APPROVED => 'Approved',
      self::REJECTED => 'Rejected',
      self::CANCELED => 'Canceled',
      self::REPLACED => 'Replaced',
    ];
  }
  
  public static function getAssociativeArray()
  {
    return [
      [ 'id' => self::PROCESSING, 'name' => 'Processing' ],
      [ 'id' => self::APPROVED, 'name' => 'Approved' ],
      [ 'id' => self::REJECTED, 'name' => 'Rejected' ],
      [ 'id' => self::CANCELED, 'name' => 'Canceled' ],
      [ 'id' => self::REPLACED, 'name' => 'Replaced' ],
    ];
  }
}