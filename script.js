function showSlideUp(role) {
  const formDiv = document.getElementById("slideUpForm");
  formDiv.innerHTML = `
    <h3>${role === 'farmer' ? 'Farmer' : 'Weather Officer'} Login</h3>
    <form action="login.html" method="post">
      <input type="text" placeholder="Username" required><br>
      <input type="password" placeholder="Password" required><br>
      <button type="submit">Login</button>
    </form>
  `;
  formDiv.style.display = 'block';
}
