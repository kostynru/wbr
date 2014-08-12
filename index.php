<?php
$page = 'index';
if (isset($_COOKIE['session']) and isset($_COOKIE['PHPSESSID'])) {
    header('Location: /wbr/profile.php');
}
include_once 'header.php';
if (isset($_GET['agreement'])) {
    ?>
    <h3>
        Cookies storing agreement
        <hr>
    </h3>
    <ol>
    <li>Cookies - it's small files that would be stored in the memory of your PC. Actually, it's safe and nobody, except us, can't get the data stored in.</li>
    <li>We aren't going to give access to cookies to any third-part users, developers or analytic systems.</li>
    <li>It would be stored there for month.</li>
    <li>Of course, you can decline cookies, but in this case we cannot guarantee you that site would act and work properly.</li>
    <li>Anyway, thank you for patience and understanding.</li></ol>
    Clicking "Log in" you are accepting this terms. If you read it and want to log in - click <a href="index.php?agr=true">there</a>

<?php } else { ?>
    <form name="login" action="/wbr/engine.php?act=login" method="POST" class="form-signin" role="form">
        <?php
        if (!empty($_GET['error'])) {
            echo '<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
            switch ($_GET['error']) {
                case 1:
                    echo 'Please, check your login and/or password';
                    break;
                default:
                    echo 'Something went wrong. Please, login again';
            }
            echo '</div>';
        }?>
        <h2 class="form-signin-heading">Jenott</h2>
        <input type="text" name="username" class="form-control" placeholder="Email address" required autofocus/><br>
        <input type="password" name="password" class="form-control" placeholder="Password" required/>
	    <div class="form-group">
			    <div class="checkbox">
				    <label>
					    <input type="checkbox" name="stay_logged_in"> Remember me
				    </label>
			    </div>
	    </div>
        <?php if(!($_GET['agr'] == 'true')){ ?>
        <div class="help-block">Clicking "Log in" you're accepting <a href="index.php?agreement=">the Cookies storing
                agreement</a></div>
        <br><?php } ?>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
        <br><a href="register.php">Register</a>
    </form>

    <div class="mastfoot">
        <div class="inner">
            <p><a href="http://twitter.com/supremepowerme" target="_blank"><img src="res/twtr.png" width="20px"></a> Shelko Kostya &copy;
                <?php echo date('Y', time()) ?>   <div class="footer_links"><a href="credits.php">Credits</a> | <a href="issues.php">Issues</a></div></p>
        </div>
    </div>
<?php } ?>