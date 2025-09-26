import { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, Link, useNavigate } from "react-router-dom";

// Route Pages
import Home from "./pages/HomePage";
import RegisterPage from "./pages/RegisterPage";
import LoginPage from "./pages/LoginPage";
import DashboardPage from "./pages/DashboardPage";

// CSS
import './css/header.css'

// Icons
import ProfileIcon from "./assets/icons/user.png"

function App() {
  const [user, setUser] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    // Vérifier la session à l'ouverture de l'application
    const checkSession = async () => {
      try {
        const response = await fetch(
          "http://localhost/parcNational/backend/controllers/AuthController.php?action=check",
          {
            method: "GET",
            credentials: "include",
          }
        );
        const data = await response.json();
        if (data.loggedIn) {
          setUser(data.user);
        }
      } catch (error) {
        console.error("Erreur session :", error);
      }
    };

    checkSession();
  }, []);

  const handleLogout = async () => {
    const res = await fetch(
      "http://localhost/parcNational/backend/controllers/AuthController.php?action=logout",
      {
        method: "POST",
        credentials: "include",
      }
    );
    const data = await res.json();

    if (data.success) {
      setUser(null);
      navigate("/");
    }
  };

  return (
    <>
    <header className="app-header">
      <nav className="app-nav">
        <div className="nav-left">
          <Link to="/">Accueil</Link>
          {user && <Link to="/sentiers">Sentiers</Link>}
          {user && <Link to="/camping">Campings</Link>}
        </div>

        <div className="nav-right">
          <Link to={user ? "/dashboard" : "/register"}>
            <img src={ProfileIcon} alt="Profil" className="profile-icon" />
          </Link>

          {user && <button onClick={handleLogout}>Déconnexion</button>}
        </div>
      </nav>
    </header>


      <main className="app-main">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/register" element={<RegisterPage setUser={setUser} />} />
          <Route path="/login" element={<LoginPage setUser={setUser} />} />
          <Route path="/dashboard" element={<DashboardPage user={user} />} />
        </Routes>
      </main>

      <footer className="app-footer">
        <p>2025 Mon Site</p>
      </footer>
    </>
  );
}

export default function AppWrapper() {
  return (
    <Router>
      <App />
    </Router>
  );
}
