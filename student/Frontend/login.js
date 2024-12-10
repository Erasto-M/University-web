document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    const errorMessage = document.getElementById("errorMessage");

    loginForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        try {
            // Ensure body content is wrapped as a string
            const response = await fetch("http://84.247.174.84/university/student/login.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                // Correctly format body content as a string
                body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
            });

            // Ensure response is valid JSON
            const result = await response.json();

            if (result.redirect) {
                // Redirect on successful login
                window.location.href = result.redirect;
            } else if (result.error) {
                // Show error message
                errorMessage.textContent = result.error;
                errorMessage.classList.remove("hidden");

                // Clear input fields
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
            }
        } catch (error) {
            console.error("Error:", error);
            errorMessage.textContent = "An unexpected error occurred. Please try again.";
            errorMessage.classList.remove("hidden");
        }
    });
});
