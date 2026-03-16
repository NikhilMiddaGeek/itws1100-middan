/* Lab 05 JavaScript
   Form validation and user interaction. */

(() => {
  "use strict";

  const DEFAULT_COMMENTS_TEXT = "Please enter your comments";

  function isBlank(value) {
    return value.trim().length === 0;
  }

  function focusAndAlert(element, message) {
    alert(message);
    element.focus();
  }

  function getField(id) {
    const element = document.getElementById(id);
    if (!element) {
      throw new Error(`Missing required element: #${id}`);
    }
    return element;
  }

  function validateForm(event) {
    const firstName = getField("firstName");
    const lastName = getField("lastName");
    const title = getField("title");
    const org = getField("org");
    const pseudonym = getField("pseudonym");
    const comments = getField("comments");

    if (isBlank(firstName.value)) {
      event.preventDefault();
      focusAndAlert(firstName, "You must enter a first name");
      return;
    }

    if (isBlank(lastName.value)) {
      event.preventDefault();
      focusAndAlert(lastName, "You must enter a last name");
      return;
    }

    if (isBlank(title.value)) {
      event.preventDefault();
      focusAndAlert(title, "You must enter a title");
      return;
    }

    if (isBlank(org.value)) {
      event.preventDefault();
      focusAndAlert(org, "You must enter an organization");
      return;
    }

    if (isBlank(pseudonym.value)) {
      event.preventDefault();
      focusAndAlert(pseudonym, "You must enter a nickname");
      return;
    }

    const commentsValue = comments.value.trim();
    if (commentsValue.length === 0 || commentsValue === DEFAULT_COMMENTS_TEXT) {
      event.preventDefault();
      focusAndAlert(comments, "You must enter comments");
      return;
    }

    alert("Form saved successfully!");
  }

  function clearDefaultCommentsText(textarea) {
    if (textarea.value === DEFAULT_COMMENTS_TEXT) {
      textarea.value = "";
    }
  }

  function restoreDefaultCommentsTextIfEmpty(textarea) {
    if (isBlank(textarea.value)) {
      textarea.value = DEFAULT_COMMENTS_TEXT;
    }
  }

  function showNicknameAlert() {
    const firstName = getField("firstName").value.trim();
    const lastName = getField("lastName").value.trim();
    const pseudonym = getField("pseudonym").value.trim();

    alert(`${firstName} ${lastName} is ${pseudonym}`);
  }

  document.addEventListener("DOMContentLoaded", () => {
    const form = getField("addForm");
    const comments = getField("comments");
    const nicknameBtn = getField("nicknameBtn");

    form.addEventListener("submit", validateForm);

    comments.addEventListener("focus", () => {
      clearDefaultCommentsText(comments);
    });

    comments.addEventListener("click", () => {
      clearDefaultCommentsText(comments);
    });

    comments.addEventListener("blur", () => {
      restoreDefaultCommentsTextIfEmpty(comments);
    });

    nicknameBtn.addEventListener("click", showNicknameAlert);

    getField("firstName").focus();
  });
})();

