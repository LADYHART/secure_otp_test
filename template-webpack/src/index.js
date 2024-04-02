import $ from "jquery";
import "bootstrap";
import "jquery-validation";
import handleRegister from "./components/register";
import handleOTP from "./components/otp_verify";
import endpoint from "./environ.json";

const validateDigit = function (e) {
  if (e.which < 48 || e.which > 57) {
    // Prevent default action if the key is not a number
    e.preventDefault();
  }
};

$(function () {
  console.log(endpoint);
  handleRegister();
  handleOTP();

  $("#otp").on("keypress", function (e) {
    validateDigit(e);
  });

  $("#mobile").on("keypress", function (e) {
    validateDigit(e);
  });
});
