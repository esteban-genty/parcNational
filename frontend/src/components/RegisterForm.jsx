import { useState } from "react";

export default function RegisterForm() {

    const [nom, setNom] = useState("");
    const [email, setEmail] = useState("");
    const [motDePasse, setMotDePasse] = useState("");
    const [message, setMessage] = useState("");

    const handleSubmit = async (event) => {
        event.preventDefault();

        try {
        const response = await fetch(
            "http://localhost/parcNational/backend/controllers/RegisterController.php",
            {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nom, email, mot_de_passe: motDePasse })
            }
        );

        const data = await response.json();
        console.log(data);
        console.log(response);

        if (data.success) {
            setMessage(data.message || "Inscription réussie");
            setNom(""); setEmail(""); setMotDePasse("");
        } else {
            setMessage(data.error || "Erreur lors de l'inscription");
        }
        } catch (error) {
        setMessage("Erreur réseau : " + error.message);
        }
    };

    return (
        <form onSubmit={handleSubmit}>
        <h2>Inscription</h2>
        <div>
            <label>Nom :</label>
            <input type="text" value={nom} onChange={(e) => setNom(e.target.value)} required />
        </div>
        <div>
            <label>Email :</label>
            <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
        </div>
        <div>
            <label>Mot de passe :</label>
            <input type="password" value={motDePasse} onChange={(e) => setMotDePasse(e.target.value)} required />
        </div>
        <button type="submit">S’inscrire</button>
        {<p>{message}</p>}
        </form>
    );
}
