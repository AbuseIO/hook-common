<?php
namespace AbuseIO\Hook;

use Log;
use Exception;
use Composer\Autoload\ClassMapGenerator;

class Common
{
    static public function call($object, $event)
    {
        // get all AbuseIO/Hook/* classes
        // use a singleton register, so we don't have to lookup the classes every time
        $hooks = HookRegistry::getInstance();

        if (empty($hooks->registry)) {
            $all_classes = array_keys(ClassMapGenerator::createMap(base_path() . '/vendor/abuseio'));
            foreach ($all_classes as $class) {
                if (preg_match("/^AbuseIO\\\\Hook\\\\/", $class) == 1) {

                    // don't match the Common, HookInterface and HookRegistry class
                    if (preg_match('/\\\\(Common|Hook(Interface|Registry))$/', $class) == 0)
                    {
                        $hooks->register($class);
                    }
                }
            }
        }

        // loop over all hooks and execute the call
        foreach ($hooks->registry as $hook)
        {
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
