<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Blade;
use Validator;

class AppServiceProvider extends ServiceProvider {

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        // extend blade
        Blade::directive('break', function($expression) {
            return "<?php break; ?>";
        });
        Blade::directive('continue', function($expression) {
            return "<?php continue; ?>";
        });
        Blade::directive('expr', function($expression) {
            return "<?php " . substr($expression, 1, -1) . "; ?>";
        });
        Blade::directive('nl2br', function($expression) {
            $var = substr($expression, 1, -1);
            $var = Blade::compileEchoDefaults($var);

            return "<?php echo nl2br(e({$var})); ?>";
        });

        // extend validation
        /*
         * before_equal:other_field_name,(date_format)
         * or
         * before_equal:date_string,(date_format)
         */
        Validator::extend('before_equal', function($attribute, $value, $parameters, $validator) {
            $format = 'Y/m/d';
            if (isset($parameters[1])) {
                $format = $parameters[1];
            }

            $param = $parameters[0];
            $attrName = null;
            if (!is_null($tmpValue = Arr::get($validator->getData(), $parameters[0]))) {
                $param = $tmpValue;
                $attrName = trans("validation.attributes.{$parameters[0]}");
            } elseif (!is_null($tmpValue = Arr::get($validator->getFiles(), $parameters[0]))) {
                $param = $tmpValue;
                $attrName = trans("validation.attributes.{$parameters[0]}");
            }
            if (array_key_exists($parameters[0], $validator->getCustomAttributes())) {
                $attrName = Arr::get($validator->getCustomAttributes(), $parameters[0]);
            }
            if (!is_null($attrName)) {
                $validator->setCustomMessages(array(
                    $attribute . '.before_equal' => str_replace(':date', $attrName, trans('validation.before_equal'))
                ));
            }

            $before = \DateTime::createFromFormat($format, $value);
            $after = \DateTime::createFromFormat($format, $param);

            return ($before && $after) && ($before <= $after);
        });
        Validator::replacer('before_equal', function($message, $attribute, $rule, $parameters) {
            return str_replace(':date', $parameters[0], $message);
        });
        #        
        /*
         * after_equal:other_field_name,(date_format)
         * or
         * after_equal:date_string,(date_format)
         */
        Validator::extend('after_equal', function($attribute, $value, $parameters, $validator) {
            $format = 'Y/m/d';
            if (isset($parameters[1])) {
                $format = $parameters[1];
            }

            $param = $parameters[0];
            $attrName = null;
            if (!is_null($tmpValue = Arr::get($validator->getData(), $parameters[0]))) {
                $param = $tmpValue;
                $attrName = trans("validation.attributes.{$parameters[0]}");
            } elseif (!is_null($tmpValue = Arr::get($validator->getFiles(), $parameters[0]))) {
                $param = $tmpValue;
                $attrName = trans("validation.attributes.{$parameters[0]}");
            }
            if (array_key_exists($parameters[0], $validator->getCustomAttributes())) {
                $attrName = Arr::get($validator->getCustomAttributes(), $parameters[0]);
            }
            if (!is_null($attrName)) {
                $validator->setCustomMessages(array(
                    $attribute . '.after_equal' => str_replace(':date', $attrName, trans('validation.after_equal'))
                ));
            }

            $before = \DateTime::createFromFormat($format, $param);
            $after = \DateTime::createFromFormat($format, $value);

            return ($before && $after) && ($before <= $after);
        });
        Validator::replacer('after_equal', function($message, $attribute, $rule, $parameters) {
            return str_replace(':date', $parameters[0], $message);
        });
        #      
        /*
         * json_file:(min),(max)
         */
        Validator::extend('json_file', function($attribute, $value, $parameters, $validator) {
            $arr = json_decode($value, true);
            if (!is_array($arr)) {
                $validator->setCustomMessages(array(
                    $attribute . '.json_file' => trans('validation.json_file.format')
                ));
                return false;
            }
            if (isset($parameters[0]) && count($arr) < intval($parameters[0])) {
                if (count($arr) == 0) {
                    $validator->setCustomMessages(array(
                        $attribute . '.json_file' => trans('validation.json_file.required')
                    ));
                } else {
                    $validator->setCustomMessages(array(
                        $attribute . '.json_file' => trans('validation.json_file.min')
                    ));
                }
                return false;
            }
            if (isset($parameters[1]) && count($arr) > intval($parameters[1])) {
                $validator->setCustomMessages(array(
                    $attribute . '.json_file' => trans('validation.json_file.max')
                ));
                return false;
            }
            return true;
        });
        Validator::replacer('json_file', function($message, $attribute, $rule, $parameters) {
            if (isset($parameters[0])) {
                $message = str_replace(':min', $parameters[0], $message);
            }
            if (isset($parameters[1])) {
                $message = str_replace(':max', $parameters[1], $message);
            }
            return $message;
        });
        #    
        /*
         * json_editor:(bool(true|false):content required, default false)
         */
        Validator::extend('json_editor', function($attribute, $value, $parameters, $validator) {
            $arr = json_decode($value, true);
            if (!is_array($arr)) {
                $validator->setCustomMessages(array(
                    $attribute . '.json_editor' => trans('validation.json_editor.format')
                ));
                return false;
            }
            if (isset($parameters[0]) && strtolower($parameters[0]) == 'true' && count($arr) <= 0) {
                $validator->setCustomMessages(array(
                    $attribute . '.json_editor' => trans('validation.json_editor.required')
                ));
                return false;
            }
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
