<?php

namespace Travis;

abstract class VueForm
{
	/**
     * Form rules.
     *
     * @var	$rules	array
     */
	public static $rules = [];

	/**
     * Form input.
     *
     * @var	$input	array
     */
	public static $input = [];

	/**
     * Form errors.
     *
     * @var	$error	object
     */
	public static $errors = null;

	/**
     * Response code.
     *
     * @var	$code	int
     */
	public static $code = 200;

	/**
     * Check if field value exists.
     *
     * @param   string  $field
     * @return  bool
     */
    public static function has($field)
    {
        // return
        return ex(static::$input, $field) ? true : false;
    }

	/**
     * Get field value from stored value.
     *
     * @param   string  $field
     * @param   string  $default
     * @return  string
     */
    public static function get($field, $default = null)
    {
        return ex(static::$input, $field, $default);
    }

    /**
     * Return array of all input.
     *
     * @return  array
     */
    public static function all()
    {
        return static::$input;
    }

    /**
     * Validate the form.
     *
     * @return  boolean
     */
	public static function is_valid()
	{
		// capture
		static::$input = \Request::input();

		// validate
		$validate = \Validator::make(static::$input, static::$rules);

	    // if fails...
	    if (!$validate->passes())
	    {
	    	// save errors
	    	static::$errors = $validate->errors();

	    	// save code
	    	static::$code = 422;

	    	// return
	    	return false;
	    }

	    // return
		return true;
	}

	/**
     * Return response object.
     *
     * @param	array 	$payload
     * @return  boolean
     */
	public static function response($payload = true)
	{
		return response(static::$errors ? static::$errors : $payload, static::$code);
	}
}