/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function () {

    var pathname = window.location.pathname;

    var arrayRoot = pathname.split('/');

    switch (arrayRoot[3]) {
        case 'login':
            $('form').attr({
                'id': 'loginform',
                'class': 'form-horizontal',
                'role': 'form'
            });

            var htmlLogin = '<div id="loginbox" style="margin-top:50px;"'
                        + 'class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">'
                        + '<div class="panel panel-info">'
                            + '<div class="panel-heading">'
                                + '<div class="panel-title">Sign In</div>'
                                + '<div style="float:right; font-size: 80%; position: relative; top:-10px">'
                                    + '<a href="/web/app.php/resetting">¿Olvidó su clave?</a>'
                                + '</div>'
                            + '</div>'
                            + '<div class="panel-body" style="padding-top:30px">'
                            + '</div>'
                        + '</div>'
                    + '</div>';
            var span1 = '<div id="input1" class="input-group" style="margin-bottom: 25px">'
                    +'<span class="input-group-addon">'
            +'<i class="glyphicon glyphicon-user"></i>'
            +'</span></div>';
            var span2 = '<div id="input2" class="input-group" style="margin-bottom: 25px">'
                    +'<span class="input-group-addon">'
                    +'<i class="glyphicon glyphicon-lock"></i>'
                    +'</span></div>'
                    +'<div id="input3" class="input-group">'
                    +'<div class="input-group">'
                    +'<label id="lbaChk">Recordarme</label>'
                    +'</div>'
                    +'</div>'
            +'<div class="form-group" style="margin-top:10px">'
            +'<div id="div-btns" class="col-sm-12 controls">'
            +'<a id="btn-fblogin" class="btn btn-primary" href="#">Login con Facebook</a>'
            +'</div>'
            +'</div>'
            +'<div class="form-group">'
            +'<div class="col-md-12 control">'
            +'<div style="border-top: 1px solid#888; padding-top:15px; font-size:85%">'
            +'¿No tiene clave?' 
            +'<a onclick="$(\'#loginbox\').hide(); $(\'#signupbox\').show()" href="/web/app.php/register"> Registrate aquí </a>'
            +'</div>'
            +'</div>'
            +'</div>';

            $('#loginform').before(htmlLogin);
            
            $('#username').attr({
                'class': 'form-control',
                'placeholder': 'email'
            });

            $('#password').attr({
                'class': 'form-control',
                'placeholder': 'Contraseña'
            });
                        
            $('label[for=username]').remove();
            $('label[for=remember_me]').remove();
            $('#loginform').appendTo('.panel-body');
            $('label[for=password]').remove();
            $('#username').before(span1);
            $('#password').before(span2);
            $('#username').appendTo('#input1');
            $('#password').appendTo('#input2');
            $('#remember_me').appendTo('#lbaChk');
            $("#_submit").appendTo("#div-btns");
            break;
        case 'register':

            $('form').attr({
                'id': 'register-form',
                'class': 'text-left'
            });

            $('#fos_user_registration_form_email').attr({
                'class': 'form-control',
                'placeholder': 'Email'
            });

            $('#fos_user_registration_form_username').attr({
                'class': 'form-control',
                'placeholder': 'Usuario'
            });

            $('#fos_user_registration_form_plainPassword_first').attr({
                'class': 'form-control',
                'placeholder': 'Contraseña'
            });

            $('#fos_user_registration_form_plainPassword_second').attr({
                'class': 'form-control',
                'placeholder': 'Confirmar Contraseña'
            });

            var htmlLogin = '<div class="login-form-main-message"></div>'
                    + '<div class="main-login-form">'
                    + '<div class="login-group">'
                    + '<div id="capa_ema" class="form-group">'
                    + '</div>'
                    + '<div id="capa_use" class="form-group">'
                    + '</div>'
                    + '<div id="capa_key" class="form-group">'
                    + '</div>'
                    + '<div id="capa_rekey" class="form-group">'
                    + '</div>'
                    + '</div>'
                    + '</div>';

            $('label[for=fos_user_registration_form_email]').before(htmlLogin);
            $('label[for=fos_user_registration_form_email]').remove();
            $('#fos_user_registration_form_email').appendTo('#capa_ema');
            $('label[for=fos_user_registration_form_username]').remove();
            $('label[for=fos_user_registration_form_plainPassword_first]').remove();
            $('label[for=fos_user_registration_form_plainPassword_second]').remove();
            $('#fos_user_registration_form_username').appendTo('#capa_use');
            $('#fos_user_registration_form_plainPassword_first').appendTo('#capa_key');
            $('#fos_user_registration_form_plainPassword_second').appendTo('#capa_rekey');
            $("input[type=submit]").remove();
            var botonLogin = '<button type="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>';
            $('.main-login-form').append(botonLogin);

            break;

    }

});