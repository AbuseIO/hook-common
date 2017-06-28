<?php
namespace AbuseIO\Hook;


interface HookInterface
{
    public static function call($object, $event);

    public static function isEnabled();
}