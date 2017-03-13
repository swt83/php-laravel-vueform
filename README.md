# Vueform

A Laravel PHP package for working w/ Vue and form submissions.

## Install

Normal install via Composer.

## Usage

Make a form model:

```php
use Travis\Vueform;

class MyForm extends VueForm
{
	public static $rules = [
		'first' => 'required',
		'last' => 'required',
		'email' => 'required|email',
	];

	public static function run()
	{
		if (static::is_valid())
		{
			// capture
			$first = static::get('first');
			$last = static::get('last');
			$email = static::get('email');

			// do something
		}

		// return a response object
		return static::response();

		// This response will automatically show error code 422 and
		// your validate errors.  A successful submission will
		// return response code 200.

		/*
		// add optional message
		static::message('Success!');

		// or add a message to response method
		return static::response('You did it!);
		*/
	}
}
```

Make a route or a controller:

```php
Route::post('submit', function()
{
	return MyForm::run();
});
```

In your ``resources/assets/js/app.js`` file, setup your Vue instance (with the helper ``errors`` class):

```
require('../../../vendor/travis/vueform/public/js/vueform.js');

const app = new Vue({
    el: '#app',
    data: {
    	'step': 1,
    	'input': {
    		'first': null,
    		'last': null,
    		'email': null,
    	},
    	'errors': new errors(),
    	'errorMessage': null,
    },
    methods: {
    	onFormSubmit: function() {
    		var app = this;
    		axios.post('submit', this.input)
    			.then(function(response) {
    				app.step = 3;
    				app.errorMessage = response.data.message;
    			})
    			.catch(function(error) {
    				app.step = 2;
    				if (error.response.data.errors)
                    {
                        that.errorMessage = error.response.data.message;
                        that.errors.record(error.response.data.errors);
                    }
                    else
                    {
                        that.errorMessage = 'Sorry, something went wrong with the server!';
                    }
    			});
    	},
    	onFormClear: function() {
    		this.step = 1;
    		this.errors.clearAll();
    		for (field in this.input) {
    			this.input[field] = null;
    		}
    	}
    }
});
```

Setup your HTML form:

```html
<form method="POST" action="#" v-on:submit.prevent="onFormSubmit()">
	<div v-if="step == 2" v-text="errorMessage"></div>
	<div v-else-if="step == 3" v-text="errorMessage"></div>
	<label class="label is-hidden">First</label>
	<p class="control">
		<input name="first" type="text" v-bind:class="errors.get('first') ? 'input is-medium is-danger' : 'input is-medium'" placeholder="First" v-model='input.first' v-on:keydown="errors.clear('first')">
		<span class="help is-danger" v-if="errors.has('first')" v-text="errors.get('first')"></span>
	</p>
	<label class="label is-hidden">Last</label>
	<p class="control">
		<input name="last" type="text" v-bind:class="errors.get('last') ? 'input is-medium is-danger' : 'input is-medium'" placeholder="Last" v-model='input.last' v-on:keydown="errors.clear('last')">
		<span class="help is-danger" v-if="errors.has('last')" v-text="errors.get('last')"></span>
	</p>
    <label class="label is-hidden">Email</label>
	<p class="control">
		<input type="text" v-bind:class="errors.get('email') ? 'input is-medium is-danger' : 'input is-medium'" placeholder="Email" v-model='input.email' v-on:keydown="errors.clear('email')">
		<span class="help is-danger" v-if="errors.has('email')" v-text="errors.get('email')"></span>
	</p>
	<div class="control is-grouped">
		<p class="control">
			<button class="button is-primary is-medium" v-bind:disabled="errors.any()">Submit</button>
		</p>
		<p class="control">
			<a class="button is-link is-medium" v-on:click="onFormClear()">Cancel</a>
		</p>
	</div>
</form>
```