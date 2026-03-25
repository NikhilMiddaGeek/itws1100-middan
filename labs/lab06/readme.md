Lab 6 - JavaScript and jQuery

- Homepage: http://127.0.0.1:5500/labs/lab03/lab03b/homepage.html
- Lab 06 landing page: http://127.0.0.1:5500/labs/lab03/lab03b/lab_landing.html
- Lab 06 page: http://127.0.0.1:5500/labs/lab06/lab06_landing.html
- GitHub repo: https://github.com/NikhilMiddaGeek/itws1100-middan.git

Problem 5 explanation

If you only attach the click event to the list items that are already on the page, any new items you add later won’t react when you click them. To fix that, I used event delegation by putting the click handler on labList instead (on('click', 'li', ...)), so clicks on both the original items and the new ones get handled and they all toggle the .red class.
