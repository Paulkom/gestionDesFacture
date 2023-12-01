// Define form element
const form = document.getElementById('kt_sign_in_form');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'current_password': {
                validators: {
                    notEmpty: {
                        message: 'Current password is required'
                    }
                }
            },
            'new_password': {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },
                    callback: {
                        message: 'Please enter valid password',
                        callback: function (input) {
                            if (input.value.length > 0) {
                                return validatePassword();
                            }
                        }
                    }
                }
            },
            'confirm_password': {
                validators: {
                    notEmpty: {
                        message: 'The password confirmation is required'
                    },
                    identical: {
                        compare: function () {
                            return form.querySelector('[name="new_password"]').value;
                        },
                        message: 'The password and its confirm are not the same'
                    }
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('kt_docs_formvalidation_password_submit');
submitButton.addEventListener('click', function (e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log('validated!');

            if (status == 'Valid') {
                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click
                submitButton.disabled = true;

                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                setTimeout(function () {
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;

                    // Show popup confirmation
                    Swal.fire({
                        text: "Form has been successfully submitted!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });

                    //form.submit(); // Submit form
                }, 2000);
            }
        });
    }
});