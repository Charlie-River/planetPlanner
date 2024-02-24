//used to create stars rather than hard coding multiple star divs
document.addEventListener("DOMContentLoaded", function() {
  var starContainer = document.querySelector(".star-container");

  // will create 30 stars - increase number to increase star count
  for (var i = 0; i < 1000; i++) {
    var star = document.createElement("div");
    star.className = "star";
    star.textContent = ".";
    starContainer.appendChild(star);
  }

  var stars = document.getElementsByClassName("star");
  // uses a for loop and random number generator to randomize the position of the stars :)
  for (var i = 0; i < stars.length; i++) {
    var x = Math.floor(Math.random() * window.innerWidth);
    var y = Math.floor(Math.random() * window.innerHeight);
    stars[i].style.transform = "translate(" + x + "px, " + y + "px)";
  }
});

document.addEventListener("DOMContentLoaded", function () {
    // Get references to the login and signup forms
    var loginForm = document.getElementById("login-form");
    var signupForm = document.getElementById("signup-form");

    // Get references to the toggle links
    var toggleSignupLink = document.getElementById("toggle-signup");
    var toggleLoginLink = document.getElementById("toggle-login");

    // Add click event listeners to the toggle links
    toggleSignupLink.addEventListener("click", function (event) {
        event.preventDefault();
        loginForm.classList.add("hidden");
        signupForm.classList.remove("hidden");
    });

    toggleLoginLink.addEventListener("click", function (event) {
        event.preventDefault();
        signupForm.classList.add("hidden");
        loginForm.classList.remove("hidden");
    });
});
