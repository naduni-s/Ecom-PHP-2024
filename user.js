// Enable editing of fields and add a visual cue
function enableEdit(id) {
  const field = document.getElementById(id);
  field.disabled = false;
  field.classList.add('bg-white', 'border-blue-500');
}

// Save changes with validation and modern fetch API
async function saveChanges() {
  const username = document.getElementById('usernameField').value;
  const email = document.getElementById('emailField').value;
  const phone = document.getElementById('phoneField').value;
  const currency = document.getElementById('currencyField').value;
  const address = document.getElementById('addressField').value;

  // Simple validation (can be expanded)
  if (!username || !email || !phone || !currency || !address) {
      alert('Please fill in all fields.');
      return;
  }

  try {
      // Simulate API call
      const response = await fetch('update_profile.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
              username,
              email,
              phone,
              currency,
              address,
          }),
      });

      if (response.ok) {
          alert('Changes saved successfully!');
      } else {
          alert('Error saving changes.');
      }

      // Disable fields again
      const fields = ['usernameField', 'emailField', 'phoneField', 'currencyField', 'addressField'];
      fields.forEach(fieldId => {
          const field = document.getElementById(fieldId);
          field.disabled = true;
          field.classList.remove('bg-white', 'border-blue-500');
      });
  } catch (error) {
      alert('Failed to save changes. Please try again later.');
  }
}

function resetForm() {
  document.getElementById('userInfoForm').reset();
  const fields = ['usernameField', 'emailField', 'phoneField', 'currencyField', 'addressField'];
  fields.forEach(fieldId => {
      const field = document.getElementById(fieldId);
      field.disabled = true;
      field.classList.remove('bg-white', 'border-blue-500');
  });
}
function confirmDelivery(orderID) {
    const confirmed = confirm("Did you receive your order?");
    if (confirmed) {
        document.getElementById('orderIDInput').value = orderID;
        document.getElementById('confirmForm').submit();
    }
}
  

function handleLogout() {
  if (confirm('Do you want to log out?')) {
      window.location.href = 'logout.php';
  }
}
