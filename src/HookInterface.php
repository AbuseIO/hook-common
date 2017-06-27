<?php
namespace AbuseIO\Hook;


interface HookInterface
{
    public function call($object, $event);
}