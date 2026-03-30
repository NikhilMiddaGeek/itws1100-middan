$(document).ready(function() {
  let score = 85;
  let passing = 70;
  if (score >= passing) {
    $("#result").text("You passed!").css("color", "green");
  } else {
    $("#result").text("Try again.").css("color", "red");
  }
});
.highlight {
  font-family: "Courier New", monospace;
  font-weight: 700;
  font-size: 120%;
  background-color: #ffffcc;
  padding: 5px;
}
$(".card").on("click", function() {
  $(this).toggleClass("highlight");
});
.sidebar { height: 100%; width: 250px; float: left; clear: left; }
.content { margin-left: 260px; }
