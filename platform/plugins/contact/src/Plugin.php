<?php

namespace Botble\Contact;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('contacts');
    }
}
