import { useState } from "react";
import { useNavigate } from "react-router-dom";
import bgCalanque from "../assets/bg-calanque.jpg";

export default function RegisterForm() {

    
    const [nom, setNom] = useState("");
    const [email, setEmail] = useState("");
    const [motDePasse, setMotDePasse] = useState("");
    const [message, setMessage] = useState("");
    const navigate = useNavigate(); 

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await fetch(
        "http://localhost/parcNational/backend/controllers/AuthController.php?action=register",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ nom, email, mot_de_passe: motDePasse }),
          credentials: "include"
        }
      );

      const data = await response.json();
      /*
      console.log(data);
      console.log(response);
      */

      
      if (data.success) {
        setNom(""); 
        setEmail(""); 
        setMotDePasse("");
        navigate("/dashboard")
      } else {
        setMessage(data.error || "Erreur lors de l'inscription");
      }
    } catch (error) {
      setMessage("Erreur " + error.message);
    }
  };

  return (
    <>
      <section className="register-img">
        <img src={bgCalanque} alt="Inscription" />
      </section>

      <section className="register">
        <h1>Inscription</h1>
        <p>Veuillez entrer vos coordonnées</p>
        <form onSubmit={handleSubmit}>
          <div>
            <label>Nom</label>
            <input type="text" value={nom} onChange={(e) => setNom(e.target.value)} required />
          </div>
          <div>
            <label>Email</label>
            <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
          </div>
          <div>
            <label>Mot de passe</label>
            <input type="password" value={motDePasse} onChange={(e) => setMotDePasse(e.target.value)} required />
          </div>
          <button type="submit">S’inscrire</button>
          {message && <p id="erreur">{message}</p>}
        </form>
            <p id="connection">Déjà inscrit ? <a id="link" href="/login">Se connecter</a></p>
      </section>
    </>
  );
}
