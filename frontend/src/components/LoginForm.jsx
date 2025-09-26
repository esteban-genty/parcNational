import { useState } from "react";
import { useNavigate } from "react-router-dom";
import bgCalanque from "../assets/bg-connexion.jpg";

export default function LoginPage({ setUser }) {
  const [email, setEmail] = useState("");
  const [motDePasse, setMotDePasse] = useState("");
  const [message, setMessage] = useState("");
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await fetch(
        "http://localhost/parcNational/backend/controllers/AuthController.php?action=login",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ email, mot_de_passe: motDePasse }),
          credentials: "include",
        }
      );

      const data = await response.json();

      if (data.success) {
        setUser(data.user);
        navigate("/dashboard");
      } else {
        setMessage(data.error || "Erreur lors de la connexion");
      }
    } catch (error) {
      setMessage("Erreur : " + error.message);
    }
  };

  return (
    <>
      <section className="login-img">
        <img src={bgCalanque} alt="Connexion" />
      </section>

      <section className="login">
        <h1>Connexion</h1>
        <form onSubmit={handleSubmit}>
          <div>
            <label>Email</label>
            <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
          </div>
          <div>
            <label>Mot de passe</label>
            <input type="password" value={motDePasse} onChange={(e) => setMotDePasse(e.target.value)} required />
          </div>
          <button type="submit">Se connecter</button>
          {message && <p id="erreur">{message}</p>}
        </form>
        <p id="connection">
          Créer un compte ? <a id="link" href="/register">S'inscrire</a>
        </p>
      </section>
    </>
  );
}
