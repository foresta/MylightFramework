<?php

static class DateUtil {

  public static function now(){
      return date("Y-m-d H:i:s");
  }

  //------------- TODO : not implemantation ------------------- //

  public static function addYearsAtNow($addYear){
    return date("Y-m-d H:i:s", strtotime($addYear . " year"));
  }

  // 今日から何日後または何日前を取得
  public static function addDaysAtNow($addDate){
    return date("Y-m-d H:i:s", strtotime($addDate . " day"));
  }

  public static function addHoursAtNow($addHour){
    return date("Y-m-d H:i:s", strtotime($addHour . " hour"));
  }

  public static function addMinutesAtNow($addMinute){
    return date("Y-m-d H:i:s", strtotime($addMinute . " minute"));
  }

  public static function addSecondsAtNow($addSecond){
    return date("Y-m-d H:i:s", strtotime($addSecond . " second"));
  }

  public static function isDate(){
    return true;
  }

} 