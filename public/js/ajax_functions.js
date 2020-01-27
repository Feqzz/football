function change_bet(user_id, match_id, bet, bet_id = null)
{
    var change_button_str = match_id + "bet=" + bet;
    var button = document.getElementById(change_button_str);
    button.style.background = "#17a2b8";
    button.style.color = "white";

    for(var i = 1; i <= 2; i++)
    {
        var str = match_id + "bet=" + (bet + i) % 3;
        var other_button = document.getElementById(str);
        other_button.style.background = "#f8f9fa";
        other_button.style.color = "black";
    }

    $.ajax
    ({
        type: "POST",
        url: "/football/ajax/bet",
        data:
        {
            'user_id' : user_id,
            'match_id' : match_id,
            'bet' : bet,
            'bet_id' : bet_id,
        },
        success: function(response)
        {
            $("#posts").append(response);
        }
    });
}

function login()
{
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    $.ajax
    ({
        type: "POST",
        url: "/login/ajax/login",
        data:
        {
            'username' : username,
            'password' : password,
        },
        success: function(response)
        {
            if (response.status == "success")
            {
                window.location.href = "/football";
            }
            else if (response.status == "error")
            {
                document.getElementsByName("password")[0].value = "";
                document.getElementsByName("username")[0].value = "";
                document.getElementById("err_msg").innerText = "Username or Password is incorrect.";
            }
        },
    });
}

function choose_league(league_id)
{
    $.ajax
    ({
        type: "POST",
        url: "/football/ajax/choose_league",
        data:
        {
            'league_id' : league_id,
        },
        success: function(response)
        {
            window.location.href = "/football/fixtures";
        },
        complete: function(response)
        {
            console.log("complete");
        },
    })
}

function sign_up()
{
    document.getElementsByName("password")[0].value = "";
    document.getElementsByName("username")[0].value = "";

    document.getElementById("title").innerText = "Register";
    document.getElementById("err_msg").innerText = "";
    var login_btn = document.getElementById("login-btn");
    var sign_up_text = document.getElementById("sign-up-text");
    login_btn.parentNode.removeChild(login_btn);
    sign_up_text.parentNode.removeChild(sign_up_text);

    var register_btn = document.createElement("button");
    register_btn.textContent = "Register";
    register_btn.setAttribute("class","btn btn-success btn-block");
    register_btn.setAttribute("id","register-btn");
    register_btn.setAttribute("onclick","register()");
    document.getElementById("button-group").appendChild(register_btn);

    var sign_in_text = document.createElement("p");
    sign_in_text.textContent = "Already have an account? ";
    sign_in_text.setAttribute("id","sign-in-text");
    document.getElementById("text-place").appendChild(sign_in_text);

    var sign_in_click = document.createElement("a");
    sign_in_click.textContent = "Sign in";
    sign_in_click.setAttribute("href","/");
    document.getElementById("sign-in-text").appendChild(sign_in_click);
}

function register()
{
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    if (password)
    {
        $.ajax
        ({
            type: "POST",
            url: "/login/ajax/register",
            data:
                {
                    'username': username,
                    'password': password,
                },
            success: function (response) {
                if (response.status == "success") {
                    window.location.href = "/";
                } else if (response.status == "error") {
                    document.getElementsByName("password")[0].value = "";
                    document.getElementsByName("username")[0].value = "";
                    document.getElementById("err_msg").innerText = "Username already exists";
                }
            },
        });
    }
    else
    {
        document.getElementById("err_msg").innerText = "Password can't be empty";
    }
}

function logout()
{
    $.ajax
    ({
        url: "/login/ajax/logout",
    });
}