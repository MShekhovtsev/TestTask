<?php

namespace App\Libraries\Extensions\FormBuilder\Converter\JqueryValidation;

use Lang;
use App\Libraries\Extensions\FormBuilder\Helper;

class Message extends \App\Libraries\Extensions\FormBuilder\Converter\Base\Message {

	public function ip($parsedRule, $attribute, $type) 
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name']);
		return ['data-msg-ipv4' => $message];
	}
	
	public function same($parsedRule, $attribute, $type) 
	{
		$message = Lang::get('validation.'.$parsedRule['name'], ['attribute' => $attribute]);
		return ['data-msg-equalto' => $message];
	}
	
	public function alpha($parsedRule, $attribute, $type) 
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name']);
		return ['data-msg-regex' => $message];
	}
	
	public function alphanum($parsedRule, $attribute, $type) 
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name']);
		return ['data-msg-regex' => $message];
	}

	public function integer($parsedRule, $attribute, $type)
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name']);
		return ['data-msg-number' => $message];
	}

	public function numeric($parsedRule, $attribute, $type)
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name']);
		return ['data-msg-number' => $message];
	}

	public function max($parsedRule, $attribute, $type)
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name'], ['max' => $parsedRule['parameters'][0]], $type);
		switch ($type) {
			case 'numeric':
				return ['data-msg-max' => $message];
				break;

			case 'file':
				return ['data-msg-maxfilesize' => $message];
				break;
			
			default:
				return ['data-msg-maxlength' => $message];
				break;
		}
	}
	
	public function min($parsedRule, $attribute, $type)
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name'], ['min' => $parsedRule['parameters'][0]], $type);
		switch ($type) {
			case 'numeric':
				return ['data-msg-min' => $message];
				break;

			case 'file':
				return ['data-msg-minfilesize' => $message];
				break;
			
			default:
				return ['data-msg-minlength' => $message];
				break;
		}
	}
	
	public function between($parsedRule, $attribute, $type)
	{
		$message = Helper::getValidationMessage($attribute, $parsedRule['name'], ['min' => $parsedRule['parameters'][0], 'max' => $parsedRule['parameters'][1]], $type);
		switch ($type) {
			case 'numeric':
				return ['data-msg-range' => $message];
				break;
			
			default:
				return ['data-msg-minlength' => $message, 'data-msg-maxlength' => $message];
				break;
		}
	}

	public function mimes($parsedRule, $attribute, $type)
	{
		$value = implode(',',$parsedRule['parameters']);
		$message = Helper::getValidationMessage($attribute, $parsedRule['name'], ['value' => $value]);
		return ['data-msg-accept' => $message];
	}

}
