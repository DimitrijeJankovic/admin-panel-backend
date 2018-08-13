    var jsonLanguageObject;

    function getQueryParams(qs) {
        qs = qs.split('+').join(' ');

        var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

        while (tokens = re.exec(qs)) {
            params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
        }

        return params;
    }

    function CheckPassword() {

        var pfront = document.getElementById("newPass").value;
        var password = document.getElementById("confPass").value;

        if (pfront != "" && pfront == password) {
            if (password.length < 1) {
                alert(GetLanguageValue('Reset_Password_AtLeast1'));//"Error: Password must contain at least eight characters!");
                return false;
            }
            /*
            re = /[0-9]/;
            if (!re.test(password)) {
                alert(GetLanguageValue('Reset_Password_OneNumber'));//"Error: password must contain at least one number (0-9)!");
                return false;
            }
            re = /[a-z]/;
            if (!re.test(password)) {
                alert(GetLanguageValue('Reset_Password_Lowercase'));//"Error: password must contain at least one lowercase letter (a-z)!");
                return false;
            }
            re = /[A-Z]/;
            if (!re.test(password)) {
                alert(GetLanguageValue('Reset_Password_Uppercase'));//"Error: password must contain at least one uppercase letter (A-Z)!");
                return false;
            }
            */
        } else
        {
            alert(GetLanguageValue('Reset_Password_NoMatch'));//"Passwords do not match!");
            return false;
        }

        //password ok
        return true;
    }

    $(document).ready(function(){

        $("#submit").click(function(){

            username = $("#newPass").val();
            password = $("#confPass").val();


            //var query = getQueryParams(document.location.search);
            //alert(query.token);

            if(CheckPassword())
            {
                //alert("Password ok");
                //console.log("password ok");

                var query = getQueryParams(document.location.search);
                var tokenin = query.token;
                
                $.post("reset_confirm.php", { token : tokenin, newPass:username, confPass:password }, function(data) {
                        //console.log(data);
                        if(data=="OK"){                    
                            //console.log("password ok");
                            window.location.replace("Password_reset_ok.php");

                            //alert("User has modified his password.");
                        } 
                        else
                        {
                            alert(GetLanguageValue('Reset_Password_Error'));//"Password did not changed. Please try later.");
                        }
                }
                );
            }
            else    
            {
                //alert("Password are not set properly.");
                console.log("passwords not ok");
            }
        });

        $.ajax({
           url : "../../lang/LanguageProxy.php",
           success : function(result){
               jsonLanguageObject = JSON.parse(result);

           }
       });

    });

    function GetLanguageValue(keyvalue)
    {                     
        //var keyvalue = 'welcome_subject';
        if(typeof jsonLanguageObject[keyvalue] === 'undefined') {
            return '*********** Not defined ************';
        }
        else {
            return jsonLanguageObject[keyvalue];
        }
    }
