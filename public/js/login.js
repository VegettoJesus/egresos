document.addEventListener("DOMContentLoaded", function() {
    const pass = document.getElementById("pass");
    const togglePassword = document.getElementById("togglePassword");
    const form = document.getElementById("loginForm");

    togglePassword.addEventListener("click", function() {
        const type = pass.getAttribute("type") === "password" ? "text" : "password";
        pass.setAttribute("type", type);

        togglePassword.classList.toggle('fa-eye');
        togglePassword.classList.toggle('fa-eye-slash');
    });
});

