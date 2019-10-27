# Vueform

A Laravel PHP package for working w/ Vue and form submissions.

## Install

Normal install via Composer.

## Usage

Make a form route:

```php
Route::get('login', function()
{
    return view('login');
});

Route::post('login', function()
{
    return LoginForm::run();
});
```

Make a form model:

```php
use Travis\VueForm;

class LoginForm extends VueForm
{
	public static $rules = [
		'email' => 'required|email',
        'password' => 'required',
	];

	public static function run()
	{
		if (static::validate())
		{
			// capture
			$email = static::get('email');
            $last = static::get('password');

			// do something
		}

		// return a response object
		return static::response();

		// This response will automatically show error code 422 and
		// your validate errors.  A successful submission will
		// return response code 200.

		/*
		// add optional message and return
		static::message('Success!');
		return static::response();

		// or add message as error and return
		static::error('Oops');
		return static::response();

		// or add it all to response method
		return static::response('You did it!', 200, ['extra' => 'Stuff you need to know.']);
		*/
	}
}
```

Make a form component:

```
<template>
    <form method="POST" action="#" v-on:submit.prevent="">
        <div v-if="step == 2" v-text="error_message"></div>
        <div v-else-if="step == 3" v-text="error_message"></div>
        <label>Email</label>
        <input type="text" v-bind:class="errors.get('email') ? '' : ''" placeholder="Email" v-model='input.email' v-on:keydown="errors.clear('email')">
        <div class="error" v-if="errors.has('email')" v-text="errors.get('email')"></div>
        <label>Password</label>
        <input type="password" v-bind:class="errors.get('password') ? '' : ''" placeholder="Password" v-model='input.password' v-on:keydown="errors.clear('password')">
        <div class="error" v-if="errors.has('password')" v-text="errors.get('password')"></div>
        <button v-on:click="onFormSubmit()" v-bind:class="is_waiting ? '' : ''" v-bind:disabled="errors.any()" v-text="is_waiting ? 'Loading' : 'Submit'"></button>
        or <a v-on:click="app.show_login == false">Cancel</a>
    </form>
</template>

<script>
    import vueForm from '../../../../../../vendor/travis/vueform/public/js/mixin.js'
    export default {
        mixins: [vueForm],
        data: function() {
            return {
                url: 'login',
            }
        }
    }
</script>
```

Make a form view:

```html
<login></login>
```