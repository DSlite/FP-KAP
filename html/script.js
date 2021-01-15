(function() {
    'use strict';
    window.addEventListener('load', function() {
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = document.getElementsByClassName('needs-validation');
      // Loop over them and prevent submission
      
      var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
          if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });

      var file = $("#file");
      var alertButton = $("#alert > button");

      file.on("change", function(){
        var ext = file.val().split('.').pop().toLowerCase();
        var filename = file.val().split("\\").pop();
        if ($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
          $("#submit").prop("disabled", true);
          $("#file-label").text("Pilih File...");
          $("#alert > span").text("File harus berupa gambar");
          $("#alert").removeClass("d-none");
          file.val("");
        } else {
          $("#file-label").text(filename);
          $("#submit").prop("disabled", false);
          $("#alert").addClass("d-none");
        };
      });

      alertButton.on("click", function() {
        $("#alert").addClass("d-none");
      });
    }, false);
  })();