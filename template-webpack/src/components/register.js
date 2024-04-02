import $ from "jquery";
import "bootstrap";
import "jquery-validation";
import endpoint from "./../environ.json";

export default function handleRegister() {
  $("#Register").validate({
    rules: {
      mobile: {
        required: true,
        minlength: 10,
        maxlength: 10,
        digits: true,
      },

      email: {
        required: true,
        email: true,
      },

      password: {
        required: true,
        minlength: 5,
      },
      rcode: {
        required: true,
        minlength: 6,
      },

      userpolicyaccepted: "required",
    },
    messages: {
      mobile: {
        required: "Please enter your mobile number",
        minlength: "Mobile number must be 10 digits",
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long",
      },

      email: "Please enter a valid email address",
      userpolicyaccepted: "Please accept our policy",
    },
  });

  $("#Register").on("submit", function (e) {
    e.preventDefault();
    if ($("#Register").valid()) {
      const formData = new FormData(this);

      fetch(endpoint.paths.submit.url, {
        method: "POST",
        headers: {
          "Content-Type": endpoint.paths.submit.contentType,
        },
        body: JSON.stringify(Object.fromEntries(formData)),
      })
        .then(function (response) {
          if (response.ok) {
            /** TODO: display message or make transition to form */
            $("#registerthanksPopup").modal({
              backdrop: "static",
              keyboard: false,
            });
            $("#registerthanksPopup").modal("show");
            return response.json(); // Return response data if needed
          } else {
            /** FIXME: show error on screen */
            throw new Error("Failed to submit mobile number");
          }
        })
        .catch(function (error) {
          /** FIXME: show error on screen */
          console.error("Error:", error);
        });
    }
    // $.ajax({
    //   type: "POST",
    //   url: "registerNow.php",
    //   data: new FormData(this),
    //   contentType: false,
    //   cache: false,
    //   processData: false,

    //   success: function (html) {
    //     //alert(html);
    //     var arr = html.split("~");

    //     if (arr[0] == 1) {
    //       $("#Register")[0].reset();
    //       //window.location.href = "successvisitorNow";
    //       $("#registerthanksPopup").modal({
    //         backdrop: "static",
    //         keyboard: false,
    //       });
    //       $("#registerthanksPopup").modal("show");
    //     } else if (arr[0] == 2) {
    //       document.getElementById("regtoast").innerHTML =
    //         '<font size="2" style="color:#f00;">Mobile Number allready register with us !</font>';
    //       $("#registertoast").modal({ backdrop: "static", keyboard: false });
    //       $("#registertoast").modal("show");
    //     } else if (arr[0] == 3) {
    //       document.getElementById("regtoast").innerHTML =
    //         '<font size="2" style="color:#f00;">Wrong recommendation code enter !</font>';
    //       $("#registertoast").modal({ backdrop: "static", keyboard: false });
    //       $("#registertoast").modal("show");
    //     } else if (arr[0] == 4) {
    //       document.getElementById("regtoast").innerHTML =
    //         '<font size="2" style="color:#f00;">Please verify your mobile no. !</font>';
    //       $("#registertoast").modal({ backdrop: "static", keyboard: false });
    //       $("#registertoast").modal("show");
    //     } else if (arr[0] == 0) {
    //       document.getElementById("serror").innerHTML =
    //         '<font size="2" style="color:#f00;">Some Technical error !</font>';
    //     }
    //   },
    //
  });
}
