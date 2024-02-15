<?php

namespace Botble\Shortcode\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Closure;
use Illuminate\Support\Arr;

class ShortcodeController extends BaseController
{
    /**
     * @param string $key
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function ajaxGetAdminConfig($key, BaseHttpResponse $response)
    {
        $registered = shortcode()->getAll();

        $data = Arr::get($registered, $key . '.admin_config');
        if ($data instanceof Closure) {
            $data = call_user_func($data);
        }

        return $response->setData($data);
    }
}
