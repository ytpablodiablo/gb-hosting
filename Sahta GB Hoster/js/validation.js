$(function() {

  $.validator.setDefaults({
    errorClass: 'help-block',
    highlight: function(element) {
      $(element)
        .closest('.form-control')
        .addClass('has-error');
    },
    unhighlight: function(element) {
      $(element)
        .closest('.form-control')
        .removeClass('has-error');
    },

    errorPlacement: function (error, element) {
      if (element.prop('type') === 'checkbox') {
        error.insertAfter(element.parent());
      } else {
        error.insertAfter(element);
      }
    }
 })

  $.validator.addMethod('strongPassword', function(value, element) {
    return this.optional(element) 
      || value.length >= 8
      && /\d/.test(value)
      && /[a-z]/i.test(value);
  }, '<div class="help-block with-errors">Vasa sifra mora sadrzati najmanje 8 karaktera i jedan broj.</div>')



  $("#login-form").validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
      },
    },
    messages: {
      email: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete e-mail adresu.</div>',
        email: '<div class="help-block with-errors">Molimo Vas da ukucate <em>validnu</em> e-mail adresu.</div>'
      },
      password: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete sifru.</div>'
      },
    }
  });


  $("#register-form").validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        strongPassword: true
      },
      password2: {
        required: true,
        equalTo: '#password'
      },
      fname: {
        required: true,
      },
      lname: {
        required: true,
      },
      terms: {
        required: true
      },
    },
    messages: {
      email: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete e-mail adresu.</div>',
        email: '<div class="help-block with-errors">Molimo Vas da ukucate <em>validnu</em> e-mail adresu.</div>',
      },
      password: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete sifru.</div>'
      },
      password2: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete sifru.</div>',
        equalTo: '<div class="help-block with-errors">Molimo Vas da ponovite sifru.</div>'
      },
      fname: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete ime.</div>'
      },
      lname: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete prezime.</div>'
      },
      terms: {
        required: '<div class="help-block with-errors">Molimo Vas da stiklirate dugme.</div>'
    }
  }
 })




}) 
