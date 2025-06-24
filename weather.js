const apiKey = "f3d7e36d85ecbece83df17d7e9b864a9"; // Replace with your OpenWeather API key

window.onload = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      const lat = position.coords.latitude;
      const lon = position.coords.longitude;
      fetchWeather(lat, lon);
    });
  }
};

function fetchWeather(lat, lon) {
  const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&appid=${apiKey}`;
  fetch(url)
    .then(res => res.json())
    .then(data => {
      document.getElementById("temp").innerText = `Temperature: ${data.main.temp} °C`;
      document.getElementById("desc").innerText = `Condition: ${data.weather[0].description}`;
      document.getElementById("icon").innerHTML = `<img src="https://openweathermap.org/img/wn/${data.weather[0].icon}.png" alt="icon"/>`;
    });
}
