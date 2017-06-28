<?php
namespace AbuseIO\Hook;

use Log;
use Symfony\Component\ClassLoader\ClassMapGenerator;

class Common
{
    static public function call($object, $event)
    {
        // get all the AbuseIO\Hook\* classes
        $all_classes = array_keys(ClassMapGenerator::createMap(base_path() . '/vendor/abuseio'));
        $hook_classes = [];
        foreach ($all_classes as $class) {
            if (preg_match("/^AbuseIO\\\\Hook\\\\/", $class) == 1) {
                $hook_classes[] = $class;
            }
        }

        // loop over all loops and execute the call
        foreach ($hook_classes as $hook)
        {
            if ( (preg_match('/\\\\Common$/', $hook) == 1) or
                (preg_match('/\\\\HookInterface$/', $hook) == 1))
            {
                // skip our own classes
                continue;
            }

            try {
                if ($hook::isEnabled()) {
                    $hook::call($object, $event);
                }
            } catch (Exception $e) {
                // catch all exceptions, so the hooks won't break
                // AbuseIO
                Log::warning("Error while calling external hook [" . $hook . "] : " . $e->getMessage());
            }
        }
    }
}