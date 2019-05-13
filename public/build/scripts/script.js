var loginForm = false;
var avatarBlockOpened = false;
var commentFormOpened = false;
var postFormOpened = false;
var slideIndex = 1;

$(document).on("click", function(event) {
  // If user clicks inside the element, do nothing
  if (event.target.closest("#loginForm")) {
    return;
  }

  if (loginForm === true) {
    //loginForm = false;
    //$("#loginForm").animate({ top: "-650px" }, 10, "swing");
    console.log("outside");
  }
});

$(document).on("click", "#loginBtn", function() {
  if (loginForm === true) {
    $("#loginForm")
      .animate({ top: "120px" }, 500, "swing")
      .animate({ top: "-650px" }, 100, "swing");
  } else {
    $("#loginForm").animate({ top: "100px" }, 400, "swing");
  }
  loginForm = !loginForm;
});

$(document).on("click", "#loginForm span", function() {
  loginForm = false;
  $("#loginForm")
    .animate({ top: "120px" }, 500, "swing")
    .animate({ top: "-650px" }, 100, "swing");
});

$("#displayAvatarForm").on("click", function() {
  var avatarForm = $("#changeAvatar");
  if (avatarBlockOpened == false) {
    avatarForm.css({
      display: "block"
    });
    avatarBlockOpened = true;
  } else {
    avatarForm.css({
      display: "none"
    });
    avatarBlockOpened = false;
  }
});

$("#addPost").on("click", function() {
  var postForm = $("#postForm");
  if (postFormOpened == false) {
    postFormOpened = true;
    postForm.css({
      display: "block"
    });
  } else {
    postFormOpened = false;
    postForm.css({
      display: "none"
    });
  }
});

$("#addComment").on("click", function() {
  var commentForm = $("#commentForm");
  if (commentFormOpened == false) {
    commentForm.css({
      display: "block"
    });
    commentFormOpened = true;
  } else {
    commentForm.css({
      display: "none"
    });
    commentFormOpened = false;
  }
});

function displayReportedText() {
  if (reportedText === false) {
    reportedText = true;
    this.nextElementSibling.style.display = "block";
    this.style.display = "none";
  } else if (reportedText === true) {
    reportedText = false;
    this.nextElementSibling.style.display = "none";
  }
}

function displayReported() {
  if (reportedText === true) {
    reportedText = false;
    this.parentElement.style.display = "none";
    $(".hiddenReportLink").css("display", "inline");
  }
}

$(".hiddenReportLink").on("click", displayReportedText);
$(".hiddenReportCross").on("click", displayReported);

$(".deleteCom").on("click", function() {
  var c = confirm("Voulez vous supprimer ce commentaire?");
  if (c == false) {
    return false;
  }
});

$(".reportCom").on("click", function() {
  var c = confirm("Voulez vous signaler ce commentaire?");
  if (c == false) {
    return false;
  }
});

$(".report").on("click", function() {
  var c = confirm("Voulez vous signaler ce chapitre?");
  if (c == false) {
    return false;
  }
});

$(".delete").on("click", function() {
  var c = confirm("Voulez vous supprimer ce chapitre?");
  if (c == false) {
    return false;
  }
});

activeLinks = () => {
  var url_string = window.location.href;
  var url = new URL(url_string);
  var page = url.pathname;
  var menuLinks = document.getElementsByClassName("menuLinks");

  switch (page) {
    case "/histoires-en-cours":
      [].forEach.call(menuLinks, function(el) {
        el.classList.remove("active");
      });
      menuLinks[1].classList.add("active");
      break;
    case "/histoires-termin%C3%A9es":
      [].forEach.call(menuLinks, function(el) {
        el.classList.remove("active");
      });
      menuLinks[2].classList.add("active");
  }
};

activeLinks();
