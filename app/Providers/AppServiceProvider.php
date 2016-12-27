<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Form::macro('group', function ($type, $name, $label = null){

            if(in_array($type, ['text', 'textarea', 'select', 'password', 'email', 'submit'])){
                $errors = \Session::get('errors');

                $output =   '<div class="form-group' . ($errors ? $errors->has($name) ? ' has-error' : '' : '') . '">'.
                            '      <label for="name" class="col-md-4 control-label">' . ($type !== 'submit' ? $label : '') . '</label>'.
                            '      <div class="col-md-6">';

                switch ($type){
                    case 'password':
                        $output .= \Form::$type($name, ['class' => 'form-control']);
                        break;
                    case 'submit':
                        $output .= \Form::$type($name, ['class' => 'btn btn-default']);
                        break;

                    default:
                        $output .= \Form::$type($name, null, ['class' => 'form-control']);
                }

                $output .= '      </div>';

                if ($errors && $errors->has($name)){
                    $output .=  '<span class="help-block">'.
                                '   <strong>' . $errors->first($name) . '</strong>'.
                                '</span>';
                }

                $output .= '</div>';
                return $output;
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
