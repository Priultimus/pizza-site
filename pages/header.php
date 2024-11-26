<?php 
if (!(headers_sent())) {
    session_start();
}
$href = '#';
$text = 'SIGN IN';
$class = 'sign-in-link';
$loggedIn = false;
if (isset($_SESSION['loginID'])) {
    $href = '../server/logout.php';
    $name = htmlspecialchars($_SESSION['f_name']) . ' ' . htmlspecialchars($_SESSION['l_name']);
    $text = 'SIGNED IN AS: ' . $name;
    $class = 'signed-in-link';
    $loggedIn = true;
}
?>

<link rel="stylesheet" type="text/css" href="../css/globals.css">
<script src="../scripts/global.js" defer></script>
<header class="header">
    <script src="../scripts/signup.js" defer></script>
    <div id="wordmark" onclick="location.href='../pages/index.php'">
      <img src="../images/logo.svg" alt="Pizza Shop Logo">
      <h2>PIZZA SHOP</h2>
    </div>
    <a id='sign-in' class="<?php echo $class ?>" href="<?php echo $href ?>"><?php echo $text ?></a>
    <a id='mobile-sign-in' class="mobile mobile-burger" href="<?php echo $href ?>">
        <div class='mobile sign-in-icons'>
            <svg class='person-icon' width="100%" height="100%" viewBox="0 0 76 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path stroke='currentColor' stroke-width="6" d="M71.8254 72.4822C72.0348 75.5031 72.0473 79.2003 72.0187 84.0307L3.0233 83.525C3.06548 78.6947 3.1322 74.9981 3.38578 71.9806C3.68681 68.3986 4.23632 66.0213 5.18084 64.0616C7.29024 59.685 10.8519 56.1751 15.259 54.1301C17.2324 53.2144 19.6174 52.6998 23.2035 52.4513C26.8214 52.2006 31.4092 52.2322 37.7519 52.2787C44.0945 52.3252 48.6823 52.3608 52.2961 52.6645C55.8782 52.9656 58.2554 53.5151 60.2151 54.4596C64.5918 56.569 68.1016 60.1307 70.1466 64.5378C71.0623 66.5111 71.577 68.8962 71.8254 72.4822ZM59.1783 24.9297C59.0913 36.8036 49.3951 46.3586 37.5213 46.2716C25.6475 46.1846 16.0925 36.4884 16.1795 24.6146C16.2665 12.7408 25.9627 3.18572 37.8365 3.27275C49.7103 3.35978 59.2654 13.0559 59.1783 24.9297Z"/>
            </svg>
            <svg width="18" height="11" viewBox="0 0 18 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.415638 2.2881C-0.126682 1.72872 -0.126682 0.932209 0.347847 0.423795C0.890177 -0.135483 1.73757 -0.135483 2.22906 0.3899L8.99127 7.55888L15.7534 0.3899C16.2619 -0.135483 17.0923 -0.118536 17.6516 0.423795C18.1431 0.915282 18.1092 1.72872 17.5837 2.2881L10.7369 9.55868C9.6352 10.7281 8.36415 10.7281 7.26262 9.55868L0.415638 2.2881Z"
                            fill="currentColor" />
            </svg>
        </div>
    </a>


    <div class="modal user-modal">
        <form class="sign-in modal-content modal-form visible" aria-modal="true" action="../server/login.php" method="POST">
            <button type="button" class="modal-close">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.00012207" y="17.6777" width="25" height="2.5" rx="1"
                        transform="rotate(-45 0.00012207 17.6777)" fill="currentColor" />
                    <rect x="1.73669" width="25" height="2.5" rx="1" transform="rotate(44 1.73669 0)"
                        fill="currentColor" />
                </svg>
            </button>
            <h1>Welcome Back</h1>
            <div class="sign-in modal-item">
                <label for="sign-in-email">Email</label>
                <input type="email" id="sign-in-email" name="email" placeholder="john.doe@example.com">
            </div>
            <div class="sign-in modal-item">
                <label for="sign-in-password">Password</label>
                <input type="password" id="sign-in-password" name="password" placeholder="Enter your password">
            </div>
            <button type="submit" id="sign-in-submit" class="modal-submit">Sign In</button>
            <p class="account-prompt sign-up">No account? <a href="#">Make an account <u>here!</u></a></p>
        </form>
        <form class="sign-up modal-content modal-form" aria-modal="true" action = "../server/signup.php" method ="POST" onsubmit = "return validateSignup()">
            <button type="button" class="modal-close">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.00012207" y="17.6777" width="25" height="2.5" rx="1" transform="rotate(-45 0.00012207 17.6777)" fill="currentColor" />
                    <rect x="1.73669" width="25" height="2.5" rx="1" transform="rotate(44 1.73669 0)" fill="currentColor" />
                </svg>
            </button>
            <h1>Sign up</h1>
            <div class="sign-up modal-item double">
                <div class="modal-item">
                    <label for="first-name">First Name</label>
                    <input class="double-input" type="text" id="first-name" name="first-name" placeholder="John"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="modal-item">
                    <label for="last-name">Last Name</label>
                    <input class="double-input" type="text" id="last-name" name="last-name" placeholder="Doe"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <div class="sign-up modal-item">
                <label for="sign-up-email">Email</label>
                <input type="email" id="sign-up-email" name="email" placeholder="john.doe@example.com" onblur = "validateEmailField(this)" onfocus="onFocused(this)" required>
            </div>
            <div class="sign-up modal-item double">
                <div class="modal-item">
                    <label for="sign-up-password">Password</label>
                    <input class="double-input" type="password" id="sign-up-password" name="password"
                            placeholder="Enter your password"  onblur = "validatePasswordField(this)" onfocus="onFocused(this)" required>
                </div>
                <div class="modal-item">
                    <label for="sign-up-confirm-password">Confirm Password</label>
                    <input class="double-input" type="password" id="sign-up-confirm-password"
                            name="confirm-password" placeholder="Re-enter your password"  onblur = "validateNameField(this)" onfocus="onFocused(this)" required>
                </div>
            </div>
            <button type="submit" id="sign-up-submit" class="modal-submit">Submit</button>
            <p class="account-prompt sign-in">Already have an account? <a href="#">Sign in <u>here!</u></a></p>
        </form>
    </div>
</header>
<nav class="account-options">
        <?php if($loggedIn) {
        echo "<p class='account-info mobile'>$name</p>
           <hr>
          <a class='account-option mobile sign-out-link' href='$href'>Sign-out</a>";
        } else {
        echo "<a class='account-option mobile sign-in-link'>Sign in</a>
              <a id='mobile-sign-up' class='account-option mobile sign-up-link'>Sign up</a>";
        } ?>
</nav>