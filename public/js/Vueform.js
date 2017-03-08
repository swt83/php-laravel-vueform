class Errors {

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