<?php

namespace Botble\Hotel\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class FontIconField extends FormField
{

    /**
     * Get the template, can be config variable or view path.
     *
     * @return string
     */
    protected function getTemplate()
    {
        return 'plugins/hotel::forms.fields.font-icon-field';
    }
}
