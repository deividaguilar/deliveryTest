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
                'id': 'login-form',
                'class': 'text-left'
            });

            $('#username').attr({
                'class': 'form-control',
                'placeholder': 'Usuario'
            });

            $('#password').attr({
                'class': 'form-control',
                'placeholder': 'Contraseña'
            });

            var htmlLogin = '<div class="login-form-main-message"></div>'
                    + '<div class="main-login-form">'
                    + '<div class="login-group">'
                    + '<div id="capa_usr" class="form-group">'
                    + '</div>'
                    + '<div id="capa_pwd" class="form-group">'
                    + '</div>'
                    + '<div id="capa_rem" class="form-group login-group-checkbox">'
                    + '</div>'
                    + '</div>'
                    + '</div>';

            $('label[for=username]').before(htmlLogin);
            $('label[for=username]').remove();
            $('#username').appendTo('#capa_usr');
            $('label[for=password]').remove();
            $('#password').appendTo('#capa_pwd');
            $('#remember_me').appendTo('#capa_rem');
            $('label[for=remember_me]').appendTo('#capa_rem');
            $("#_submit").remove();
            var botonLogin = '<button type="submit" id="_submit" name="_submit" class="login-button"><i class="fa fa-chevron-right"></i></button>';
            $('.main-login-form').append(botonLogin);
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