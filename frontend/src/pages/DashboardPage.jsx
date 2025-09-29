import { useState, useEffect } from "react";
import { useNavigate, Link } from "react-router-dom";

// Components
import DashboardRouter from "../components/Dashboard/DashboardRouter";
import DashboardWeather from "../components/Dashboard/DashboardWeather";

// CSS
import '../css/dashboard.css'

// IMG
import bgDashboard from "../assets/bg-dashboard.jpg"

export default function Dashboard() {
  const [user, setUser] = useState(null);
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const response = await fetch(
          "http://localhost/parcNational/backend/controllers/AuthController.php?action=check",
          { method: "GET", credentials: "include" }
        );

        if (!response.ok) throw new Error("Erreur HTTP : " + response.status);
        const data = await response.json();
        console.log("Réponse API :", data);

        if (data.loggedIn) setUser(data.user);
        else navigate("/login");
      } catch (error) {
        setMessage("Erreur : " + error.message);
      }
    };
    fetchUser();
  }, [navigate]);

  if (!user) return <p>Chargement...</p>;

  return (
    <section className="dashboard-section">
      {message && <p>{message}</p>}

      <section className="dashboard-left">
        <nav>
          <ul>
            <h5 className="dashboard-title">Visiteurs</h5>
            <li><Link to="/dashboard">Tableau de bord</Link></li>
            <li><Link to="sentiers">Gestion des sentiers</Link></li>
            <li><Link to="camping">Réservations camping</Link></li>
            <li><Link to="ressources">Ressources naturelles</Link></li>
            {user.role === "admin" && <h5 className="dashboard-title">Admin</h5>}
            {user.role === "admin" && <li><Link to="comptes">Gestion des comptes</Link></li>}
            {user.role === "admin" && <li><Link to="notifications">Gestion des notifcations</Link></li>}
          </ul>
        </nav>
      </section>

      <section className="dashboard-right">

        <div className="text-img">
            <h3>Bienvenue dans votre dashboard !</h3>
            <p>Préservons ensemble ce patrimoine naturel exceptionel</p>
          </div>

        <div className="dashboard-header">
          <img src={bgDashboard} alt="Background-Calanques" />
        </div>

        <section className="informations-user">
          <p>ID : {user.id}</p>
          <p>Nom : {user.nom}</p>
          <p>Email : {user.email}</p>
          <p>Rôle : {user.role}</p>
        </section>

        <section className="dashboard-notifications">
          <p>Notification...</p>
        </section>

        <section className="dashboard-weather">
          <DashboardWeather />
        </section>

        <section className="dashboard-content">
          <DashboardRouter userRole={user.role} />
        </section>
      </section>
    </section>
  );
}
