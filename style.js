// Wait for the DOM to fully load before running scripts
document.addEventListener("DOMContentLoaded", function () {
    // 1. Password Toggle Functionality
    const passwordInput = document.getElementById("password");
    const toggleBtn = document.querySelector(".password-toggle");
    
    toggleBtn.addEventListener("click", function () {
      if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleBtn.textContent = "🙈"; // Change icon when password is visible
      } else {
        passwordInput.type = "password";
        toggleBtn.textContent = "👁"; // Change icon when hidden
      }
    });
  
    // 2. Login Button Functionality
    const loginButton = document.querySelector(".login-button");
    
    loginButton.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default form submission behavior
      const username = document.getElementById("username").value.trim();
      const password = passwordInput.value.trim();
      
      // Validate that both fields are filled
      if (username === "" || password === "") {
        alert("Please enter both username and password.");
        return;
      }
      
      // For demonstration purposes, simply log the credentials and alert the user
      console.log("Login attempted with:", username, password);
      alert("Login functionality is not implemented yet.");
      
      // In a real application, you would send these details to your server for authentication.
    });
  
    // 3. Menu Icon Functionality
    const menuIcon = document.querySelector(".menu-icon");
    
    menuIcon.addEventListener("click", function () {
      console.log("Menu icon clicked.");
      alert("Menu functionality is not implemented yet.");
      
      // Future Enhancement: Toggle a side navigation menu here.
    });
  
    // 4. Forgot Password Functionality
    const forgotPasswordLink = document.querySelector(".forgot-password");
    
    forgotPasswordLink.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent the default link behavior
      let email = prompt("Please enter your email to reset your password:");
      if (email) {
        console.log("Password reset requested for:", email);
        alert("A password reset link has been sent to " + email + " (simulated).");
        
        // Future Enhancement: Send an AJAX request to your server to handle password reset.
      }
    });
  
    // 5. Register Link Functionality
    const registerLink = document.querySelector(".register-link a");
    
    registerLink.addEventListener("click", function (e) {
      e.preventDefault(); // Prevent default link navigation
      console.log("Redirecting to the registration page.");
      alert("Redirecting to the registration page (simulated).");
      
      // Future Enhancement: Replace this alert with a real redirect, e.g.:
      // window.location.href = "registration.html";
    });
  });
  