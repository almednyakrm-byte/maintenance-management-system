<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            -webkit-transition: background-position 2s linear;
            transition: background-position 2s linear;
        }
        
        .glassmorphic {
            background-color: #1a1d23;
            background-image: linear-gradient(180deg, #1a1d23, #1a1d23), linear-gradient(135deg, #fff, #fff);
            background-size: 100% 100%, 100% 100%;
            background-position: 0% 0%, 0% 0%;
            -webkit-transition: background-position 2s linear;
            transition: background-position 2s linear;
        }
        
        .glassmorphic:hover {
            background-position: 0% -100%;
        }
    </style>
</head>
<body>
    <div class="h-screen flex justify-center items-center bg-gray-200">
        <div class="glassmorphic p-8 bg-white rounded-lg shadow-2xl w-96">
            <h2 class="text-3xl text-center text-slate-700 mb-4">Login</h2>
            <form id="login-form">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-700">Username</label>
                    <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="mb-4 text-center">
                    <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
                </div>
                <div class="text-center text-sm text-gray-600">
                    Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'dashboard.php';
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        });
    </script>
</body>
</html>


This code includes a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. The form includes validation rules using standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the fetch API to submit the credentials to the backend authentication script and handle the response or error alerts dynamically. The code also includes a direct link to the register page.