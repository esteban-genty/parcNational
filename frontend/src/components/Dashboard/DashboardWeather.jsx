import { useEffect, useState } from "react";

export default function WeatherWidget() {
  const [weather, setWeather] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    async function fetchWeather() {
      try {
        const res = await fetch(
          "https://api.open-meteo.com/v1/forecast?latitude=43.2167&longitude=5.4333&current_weather=true"
        );
        if (!res.ok) throw new Error("Erreur API");
        const data = await res.json();
        setWeather(data.current_weather);
      } catch (err) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    }

    fetchWeather();
  }, []);

  function getWeatherDescription(code) {
    switch (code) {
      case 0:
        return "Ciel dégagé";
      case 1:
      case 2:
      case 3:
        return "Nuageux";
      case 61:
      case 63:
      case 65:
      case 80:
      case 81:
      case 82:
        return "Pluie";
      default:
        return "Vent / autre";
    }
  }

  if (loading) return <p>Chargement...</p>;
  if (error) return <p>Erreur : {error}</p>;

  return (
 <section className="weather">
  <ul className="weather-array">
    <li id="temperature">
      <h4>{weather.temperature}°C</h4>
      <p>Température</p>
    </li>
    <li id="wind-speed">
      <h4>{weather.windspeed} km/h</h4>
      <p>Vitesse du vent</p>
    </li>
    <li id="condition">
      <h4>{getWeatherDescription(weather.weathercode)}</h4>
      <p>Condition</p>
    </li>
  </ul>
</section>

  );
}
