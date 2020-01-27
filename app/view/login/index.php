<?php include LOGIN . "header.php";?>
<div class="container">
    <b><br><br><br><br></b>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header border-0" style="text-align: center" id="title">Login</div>
                <div class="card-body" id="card-body">
                    <div class="form-group">
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    </div>
                    <div id="button-group">
                    <button class="btn btn-success btn-block" onclick="login()" id="login-btn">Login</button>
                    </div>
                    <b><br></b>
                    <div id="text-place">
                    <p id="sign-up-text">Don't have an account? <a href="#" onclick="sign_up()">Sign up</a></p>
                    </div>
                    <h5 id="err_msg" style="text-align: center; color: red;" ></h5>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/js/ajax_functions.js"></script>
<?php include LOGIN . "footer.php";?>
