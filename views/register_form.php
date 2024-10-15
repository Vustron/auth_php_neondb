<!DOCTYPE html>
<html lang="en">
<?php require '../views/header.php'; ?>

<body class="size-full h-[100%]">

<!-- Registration Form -->
<section class="flex flex-col items-center justify-center mt-20 mb-20">
<div class="container mx-auto max-w-md bg-white rounded-2xl shadow-xl p-8" id="signup">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Sign Up</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error: </strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>

    <form method="post" action="../api/register_handler.php" class="space-y-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input type="text" name="first_name" id="first_name" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="First Name">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-user text-gray-400"></i>
            </div>
            <input type="text" name="last_name" id="last_name" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="Last Name">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input type="email" name="email" id="email" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="Email">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" name="password" id="password" required
                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="Password" oninput="checkPasswordStrength('password')">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <input type="checkbox" id="showPasswordCheckbox" onclick="togglePasswordVisibility('password')"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            </div>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
            <div id="password-strength-meter" class="h-2.5 rounded-full transition-all duration-500"></div>
        </div>
        <span id="password-strength-text" class="text-sm block mt-1"></span>

        <button type="submit" name="signUp"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
            Create Account
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">Already have an account?
            <a id="signInButton" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150" href="../views/login_form.php">
                Sign In
            </a>
        </p>
    </div>
</div>
</section>

<script>
function checkPasswordStrength(passwordFieldId) {
    const password = document.getElementById(passwordFieldId).value;
    const strengthMeter = document.getElementById('password-strength-meter');
    const strengthText = document.getElementById('password-strength-text');
    let strength = 0;

    if (password.length >= 8) strength += 1;
    if (password.match(/[a-z]/)) strength += 1;
    if (password.match(/[A-Z]/)) strength += 1;
    if (password.match(/[0-9]/)) strength += 1;
    if (password.match(/[^a-zA-Z0-9]/)) strength += 1;

    switch (strength) {
        case 1:
            strengthMeter.className = 'w-1/5 bg-red-500 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = 'Very Weak';
            break;
        case 2:
            strengthMeter.className = 'w-2/5 bg-orange-500 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = 'Weak';
            break;
        case 3:
            strengthMeter.className = 'w-3/5 bg-yellow-500 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = 'Moderate';
            break;
        case 4:
            strengthMeter.className = 'w-4/5 bg-blue-500 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = 'Strong';
            break;
        case 5:
            strengthMeter.className = 'w-full bg-green-500 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = 'Very Strong';
            break;
        default:
            strengthMeter.className = 'w-0 h-2.5 rounded-full transition-all duration-500';
            strengthText.textContent = '';
            break;
    }
}

function togglePasswordVisibility(passwordFieldId) {
    const passwordField = document.getElementById(passwordFieldId);
    const passwordFieldType = passwordField.getAttribute('type');
    passwordField.setAttribute('type', passwordFieldType === 'password' ? 'text' : 'password');
}
</script>

</body>
</html>
