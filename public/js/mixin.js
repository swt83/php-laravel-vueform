// add errors tracking object
require('./error.js');

// define
export default {
    data: function() {
        return {
            'url': null,
            'step': 1,
            'input': {},
            'errors': new vueFormErrors,
            'error_message': null,
            'is_waiting': false,
        }
    },
    methods: {
        onFormSubmit: function() {

            // flag
            this.is_waiting = true;

            // clone
            var that = this;

            // make instance
            var instance = axios.create();

            // amend timeout
            instance.defaults.timeout = 30000; // 30 seconds

            // submit post request...
            instance.post(that.url, that.input)
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
            this.input={};
        }
    }
}