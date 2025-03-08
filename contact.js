document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactform');

        form.addEventListener('submit', function(event) {
            event.preventDefault(); 

            const fullName = document.getElementById('fullName').value;
            const email = document.getElementById('email').value;
            const message = document.getElementById('message').value;

            console.log("Form submitted with the following details:");
            console.log("Full Name:", fullName);
            console.log("Email:", email);
            console.log("Message:", message);

            form.submit(); 
        });
    });
