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
     * @var	$error	array
     */
	public static $errors = [];

	/**
     * Response code.
     *
     * @var	$code	int
     */
	public static $code = 200;

	/**
     * Custom error message.
     *
     * @var	$message	string
     */
	public static $message = null;

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
     * Set the response message.
     *
     * @param	string	$message
     * @return	void
     */
    public static function message($message)
    {
    	static::$message = $message;
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
     * @param	string	$message
     * @return  boolean
     */
	public static function response($message = null)
	{
		if ($message) static::$message = $message;

		$payload = json_encode([
			'errors' => static::$errors,
			'message' => static::$message,
		]);

		return response($payload, static::$code);
	}
}