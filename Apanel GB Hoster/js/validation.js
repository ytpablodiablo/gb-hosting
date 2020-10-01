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
 })

$.validator.addMethod('IP4Checker', function(value) {
        var ip = /^(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))\.(\d|[1-9]\d|1\d\d|2([0-4]\d|5[0-5]))$/; 
        return value.match(ip);
        }, 'Invalid IP address');

  $("#dodajmasinuforma").validate({
    rules: {
      ip: {
        required: true,
	IP4Checker: true,
      },
      lokacija: {
        required: true,
      },
      datacentar: {
        required: true,
      },
      ssh2: {
        required: true,
      },
      root: {
        required: true,
      },
      sifra: {
        required: true,
      },
    },
    messages: {
      ip: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete ip adresu.</div>'
      },
      lokacija: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete lokaciju.</div>'
      },
      datacentar: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete datacentar.</div>'
      },
      ssh2: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete ssh2 port ( default: 22 ).</div>'
      },
      root: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete username masine ( default: root ).</div>'
      },
      sifra: {
        required: '<div class="help-block with-errors">Molimo Vas da unesete sifru masine.</div>'
      },
    }
  });

}) 
