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
     * Set the response message and error code at one time.
     *
     * @param   string  $message
     * @return  void
     */
    public static function error($message)
    {
        static::setMessage($message);
        static::code(422);
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
     * Set the response code.
     *
     * @param   string  $message
     * @return  void
     */
    public static function code($code)
    {
        static::$code = $code;
    }

    /**
     * Validate the form.
     *
     * @return  boolean
     */
	public static function validate()
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
     * @param   int     $code
     * @param   mixed   $extra
     * @return  boolean
     */
	public static function response($message = null, $code = null, $extra = null)
	{
		if ($message) static::setMessage($message);
        if ($code) static::setCode($code);

		$payload = json_encode([
			'errors' => static::$errors,
			'message' => static::$message,
            'data' => $extra,
		]);

		return response($payload, static::$code);
	}
}