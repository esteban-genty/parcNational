import { useState } from "react";

export default function LoginForm() {
    const [email, setEmail] = useState("");
    const [motdepasse, setMotDePasse] = useState("");
    const [message, setMessage] = useState("");

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
        const response = await fetch(
            "http://localhost/parcNational/backend/controllers/LoginController.php",
            {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, motdepasse })
            }
        );

        const data = await response.json();
        console.log(data);
        console.log(response);

        if (data.success) {
            setMessage(data.message || "Inscription réussie");
            setMotDePasse(""); setEmail("");
        } else {
            setMessage(data.error || "Erreur lors de l'inscription");
        }
        } catch (error) {
        setMessage("Erreur réseau : " + error.message);
        }
    };


  return (
    <form onSubmit={handleSubmit}>
      <h2>Connexion</h2>
      <div>
        <label>Email :</label>
        <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
      </div>
      <div>
        <label>Mot de passe :</label>
        <input type="password" value={motdepasse} onChange={(e) => setMotDePasse(e.target.value)} required />
      </div>
      <button type="submit">Se connecter</button>
      {message && <p>{message}</p>}
    </form>
  );
}
