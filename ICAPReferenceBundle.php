<?php

namespace ICAP\ReferenceBundle;

use Claroline\CoreBundle\Library\PluginBundle;

class ICAPReferenceBundle extends PluginBundle
{
    public function getRoutingPrefix()
    {
        return "reference";
    }
}