<?php

namespace Icap\ReferenceBundle;

use Claroline\CoreBundle\Library\PluginBundle;

class IcapReferenceBundle extends PluginBundle
{
    public function getRoutingPrefix()
    {
        return "reference";
    }

    public function hasMigrations()
    {
        return false;
    }

}