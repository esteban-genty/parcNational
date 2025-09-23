import { BrowserRouter as Router, Routes, Route, Link } from "react-router-dom";
import Home from "./pages/HomePage";
import RegisterPage from "./pages/RegisterPage";
import LoginPage from "./pages/LoginPage";
import './css/header.css'

function App() {
  return (
    <Router>
      <header className="app-header">
        <nav className="app-nav">
          <Link to="/">Accueil</Link>
          <Link to="/register">S'inscrire</Link>
          <Link to="/login">Se connecter</Link>
        </nav>
      </header>

      <main className="app-main">
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/login" element={<LoginPage />} />
        </Routes>
      </main>

      <footer className="app-footer">
        <p>2025 Mon Site</p>
      </footer>
    </Router>
  );
}

export default App;
