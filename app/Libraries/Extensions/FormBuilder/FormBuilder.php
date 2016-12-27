<?php

namespace App\Libraries\Extensions\FormBuilder;

use App\Libraries\Extensions\FormBuilder\Converter\Base\Converter;
use Collective\Html\FormBuilder as OldFormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;

class FormBuilder extends OldFormBuilder {

    public $converter;
    public $rules;

    public function __construct(HtmlBuilder $html, UrlGenerator $url, Factory $view, $csrfToken, Converter $converter)
    {
        parent::__construct($html, $url, $view, $csrfToken);
        $this->converter = $converter;
    }

    public function setRules(array $rules){
        $this->converter->set($rules, null);
    }

    public function model($model, array $options = [])
    {
        if($model->exists){
            $options['method'] = 'put';
            $options['url'] = $model->route('update');
        } else {
            $options['url'] = $model->route('store');
        }

        $this->converter->set($model->validationRules, null);

        return parent::model($model, $options);
    }

    public function input($type, $name, $value = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::input($type, $name, $value, $options);
    }

    public function textarea($name, $value = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::textarea($name, $value, $options);
    }

    public function select($name, $list = [], $selected = null, $options = [])
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::select($name, $list, $selected, $options);
    }

    protected function checkable($type, $name, $value, $checked, $options)
    {
        $options = $this->converter->convert(Helper::getFormAttribute($name)) + $options;
        return parent::checkable($type, $name, $value, $checked, $options);
    }


}