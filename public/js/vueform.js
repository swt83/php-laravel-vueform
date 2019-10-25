window.vueFormErrors = class {

	constructor() {
		this.fields = {};
	}

	get(field) {
		if (this.fields[field]) {
			return this.fields[field][0];
		}
	}

	has(field) {
		return this.fields.hasOwnProperty(field);
	}

	clear(field) {
		delete this.fields[field];
	}

	clearAll() {
		this.fields = {};
	}

	record(errors) {
		this.fields = errors;
	}

	any() {
		return Object.keys(this.fields).length > 0;
	}

}

window.vueFormMethods = {
    onFormSubmit: function() {

        this.is_waiting = true;

        // clone
        var that = this;

        // make instance
        var instance = axios.create();

        // amend timeout
        instance.defaults.timeout = 30000; // 30 seconds

        // submit post request...
        instance.post(this.url, this.input)
            .then(function(response) {
                that.step = 3;
                that.error_message = response.data.message;
                that.is_waiting = false;
            })
            .catch(function(error) {
                if (error.response.data.errors)
                {
                    that.errors.record(error.response.data.errors);
                    that.error_message = error.response.data.message;
                }
                else
                {
                    that.error_message = 'Sorry, something went wrong with the server!';
                }
                that.step = 2;
                that.is_waiting = false;
            });
    },
    onFormClear: function() {
        this.step = 1;
        this.errors.clearAll();
        for (field in this.input) {
            this.input[field] = null;
        }
    }
};