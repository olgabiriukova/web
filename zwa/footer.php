<footer id = "date">
  <p id="currentDate"></p>

  <script>
    function formatDate(date) {
      const day = date.getDate();
      const month = date.getMonth() + 1;
      const year = date.getFullYear();

      return `${month < 10 ? '0' + month : month}.${day < 10 ? '0' + day : day}.${year}`;
    }

    const currentDate = new Date();
    document.getElementById('currentDate').innerText = formatDate(currentDate);
  </script>
</footer>