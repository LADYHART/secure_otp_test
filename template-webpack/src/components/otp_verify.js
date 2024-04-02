import $ from "jquery";
import "bootstrap";
import "jquery-validation";
import endpoint from "./../environ.json";

export default function handleOTP() {
  $("#reg_otp").validate({
    rules: {
      mobile: {
        required: true,
        minlength: 10,
        maxlength: 10,
        digits: true,
      },
    },
    messages: {
      mobile: {
        required: "Please enter your mobile number",
        minlength: "Mobile number must be 10 digits",
      },
    },
  });

  $("#otpform").validate({
    rules: {
      otp: {
        required: true,
        minlength: 6,
        maxlength: 6,
        digits: true,
      },
    },
    messages: {
      otp: {
        required: "Please enter otp that sent to your mobile number.",
      },
    },
  });

  $("#otpsubmitForm").on("submit", function (e) {
    e.preventDefault();
    $("#otpform").valid();
    /**TODO: implement Fetch api*/
    fetch(endpoint.paths.otp.url, {
      method: "POST",
      headers: {
        "Content-Type": endpoint.paths.otp.contentType,
      },
      body: JSON.stringify({
        type: endpoint.requestType._otpval,
        otp: document.getElementById("otp").value,
      }),
    })
      .then(function (response) {
        if (response.ok) {
          /** TODO: display message or make transition to form */
          //document.getElementById("successMessage").style.display = "block";
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
  });

  $("#reg_otp").on("click", function () {
    if ($("#mobile").valid()) {
      fetch(endpoint.paths.mobile.url, {
        method: "POST",
        headers: {
          "Content-Type": endpoint.paths.mobile.contentType,
        },
        body: JSON.stringify({
          type: endpoint.requestType._mobile,
          otp: document.getElementById("mobile").value,
        }),
      })
        .then(function (response) {
          if (response.ok) {
            /** TODO: display message or make transition to form */
            //document.getElementById("successMessage").style.display = "block";
            return response.json(); // Return response data if needed
          } else {
            /** FIXME: show error on screen */
            throw new Error("Failed to submit mobile number");
          }
        })
        .then(function (json) {
          /** TODO: implete return code e.g. sms failed, invalid number etc */
          if (json.code == 0) {
            console.log("do something");
          }
          $("#otpform").modal({ backdrop: "static", keyboard: false });
          $("#otpform").modal("show");
        })
        .catch(function (error) {
          /** FIXME: show error on screen */
          console.error("Error:", error);
        });
    }
  });
}
