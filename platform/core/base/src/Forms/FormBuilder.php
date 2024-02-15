<?php

namespace Botble\Base\Forms;

use Kris\LaravelFormBuilder\FormBuilder as BaseFormBuilder;

class FormBuilder extends BaseFormBuilder
{
    /**
     * {@inheritDoc}
     */
    public function create($formClass, array $options = [], array $data = [])
    {
        return parent::create($formClass, $options, $data);
    }
}
