import { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, useNavigate, Navigate } from "react-router-dom";

// Components
import AnimatedHeader from "./components/AnimatedHeader";

// Pages
import Home from "./pages/HomePageNew";
import RegisterPage from "./pages/RegisterPage";
import LoginPage from "./pages/LoginPage";
import DashboardPage from "./pages/DashboardPage";

// CSS Global
import './css/animations.css';

function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    // Vérification session au chargement
    const checkSession = async () => {
      try {
        const response = await fetch(
          "http://localhost:8080/controllers/AuthController.php?action=check",
          {
            method: "GET",
            credentials: "include",
          }
        );
        const data = await response.json();
        console.log("Session check:", data);
        if (data.loggedIn) {
          setUser(data.user);
        }
      } catch (error) {
        console.error("Erreur session :", error);
      } finally {
        setLoading(false);
      }
    };

    checkSession();
  }, []);

  const handleLogout = async () => {
    try {
      await fetch(
        "http://localhost:8080/controllers/AuthController.php?action=logout",
        {
          method: "POST",
          credentials: "include",
        }
      );
      setUser(null);
      navigate("/");
    } catch (error) {
      console.error("Erreur logout:", error);
    }
  };

  if (loading) {
    return <div style={{ padding: '50px', textAlign: 'center' }}>Chargement...</div>;
  }

  return (
    <>
      <AnimatedHeader user={user} onLogout={handleLogout} />
      
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/register" element={<RegisterPage setUser={setUser} />} />
        <Route path="/login" element={user ? <Navigate to="/dashboard" replace /> : <LoginPage setUser={setUser} />} />
        <Route path="/dashboard/*" element={user ? <DashboardPage user={user} /> : <Navigate to="/login" replace />} />
      </Routes>
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

