<!DOCTYPE html>
<html lang="en">
<?php require '../views/header.php'; ?>

<body class="size-full h-[100%]">

<!-- Sign In Form -->
<section class="flex flex-col items-center justify-center mt-20">
<div class="container mx-auto max-w-md bg-white rounded-2xl shadow-xl p-8" id="signIn">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Sign In</h1>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error: </strong>
            <span class="block sm:inline"><?php echo htmlspecialchars($_GET['error']); ?></span>
        </div>
    <?php endif; ?>
    
    <form method="post" action="../api/login_handler.php" class="space-y-6">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-envelope text-gray-400"></i>
            </div>
            <input type="email" name="email" id="signInEmail" required
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="Email">
        </div>

        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-lock text-gray-400"></i>
            </div>
            <input type="password" name="password" id="signInPassword" required
                class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150"
                placeholder="Password">
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <input type="checkbox" id="showPasswordCheckboxSignIn" onclick="togglePasswordVisibility('signInPassword')"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
            </div>
        </div>
        <span id="password-strength-signin" class="text-sm block mt-1"></span>

        <button type="submit" name="signIn"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600">Don't have an account?
            <a id="signUpButton" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-150" href="../views/register_form.php">
                Sign Up
            </a>
        </p>
    </div>
</div>
</section>

<script>
  function togglePasswordVisibility(passwordFieldId) {
    const passwordField = document.getElementById(passwordFieldId);
    const passwordFieldType = passwordField.getAttribute('type');
    passwordField.setAttribute('type', passwordFieldType === 'password' ? 'text' : 'password');
}
</script>
</body>
</html>
