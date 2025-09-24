import { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, Link} from "react-router-dom";

// Route Pages
import Home from "./pages/HomePage";
import RegisterPage from "./pages/RegisterPage";
import LoginPage from "./pages/LoginPage";
import DashboardPage from "./pages/DashboardPage";

// CSS
import './css/header.css'

function App() {
  const [user, setUser] = useState(null);

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

  return (
    <Router>
      <header className="app-header">
        <nav className="app-nav">
          <Link to="/">Accueil</Link>
          {!user && <Link to="/register">S'inscrire</Link>}
          {!user && <Link to="/login">Se connecter</Link>}
          {user && <Link to="/dashboard">Dashboard</Link>}
        </nav>
        {user && <p>Connecté en tant que {user.nom}</p>}
      </header>

      <main className="app-main">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/dashboard" element={<DashboardPage user={user} />} />
        </Routes>
      </main>

      <footer className="app-footer">
        <p>2025 Mon Site</p>
      </footer>
    </Router>
  );
}

export default App;
